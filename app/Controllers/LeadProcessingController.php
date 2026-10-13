<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\LeadProcessing\Services\LeadProcessingService;
use App\Models\LeadSourceModel;
use App\Models\ClientModel;
use App\Helpers\Utility;
use Tenant\app\Repositories\LeadResponseRepository;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Libraries\Adf\AdfTemplate;
use Exception;

/**
 * Controller for processing leads from multiple sources (Facebook, Google, etc.)
 */
class LeadProcessingController extends BaseController
{
    private $dbase;
    private LeadProcessingService $leadProcessingService;
    private LeadSourceModel $leadSourceModel;

    public function __construct()
    {
        $this->leadProcessingService = new LeadProcessingService();
        $this->leadSourceModel = new LeadSourceModel();
    }

    /**
     * Process leads from all sources - picks first 10 pending records
     */
    public function processAllSources()
    {
        try {
            // Get first 10 pending leads regardless of source
            $leads = $this->leadSourceModel->where('status', 0)->limit(5)->findAll();

            if (empty($leads)) {
                return $this->response->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setJSON([
                        'success' => true,
                        'message' => 'No pending leads found',
                        'total_processed' => 0,
                        'total_errors' => 0
                    ]);
            }

            $totalProcessed = 0;
            $totalErrors = 0;

            foreach ($leads as $leadData) {
                try {
                    $this->processSingleLead($leadData);
                    $totalProcessed++;
                } catch (Exception $e) {
                    $totalErrors++;
                    $this->updateRecord(
                        $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(),
                        2,
                        $leadData->id
                    );
                }
            }

            return $this->response->setContentType('application/json')
                ->setStatusCode(200)
                ->setJSON([
                    'success' => true,
                    'message' => "Processed {$totalProcessed} leads with {$totalErrors} errors",
                    'total_processed' => $totalProcessed,
                    'total_errors' => $totalErrors
                ]);

        } catch (Exception $e) {
            return $this->response->setContentType('application/json')
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Error processing leads: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * Process leads by specific source ( not in use)
     *
     */
    public function processLeadsBySource(string $source = null)
    {
        if (!$source) {
            $source = $this->request->getGet('source') ?? 'facebook';
        }

        $processedCount = 0;
        $errorCount = 0;

        try {
            // Get unprocessed leads for the source
            $leads = $this->leadSourceModel->getUnprocessedLeadsBySource($source, 5);

            if (empty($leads)) {
                return [
                    'processed' => 0,
                    'errors' => 0,
                    'message' => "No unprocessed {$source} leads found"
                ];
            }

            foreach ($leads as $leadData) {
                try {
                    $this->processSingleLead($leadData);
                    $processedCount++;
                } catch (Exception $e) {
                    $errorCount++;
                    $this->updateRecord(
                        $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine(),
                        2,
                        $leadData->id
                    );
                }
            }

            $result = [
                'processed' => $processedCount,
                'errors' => $errorCount,
                'message' => "Processed {$processedCount} {$source} leads with {$errorCount} errors"
            ];

            return $this->response->setContentType('application/json')
                ->setStatusCode(200)
                ->setJSON($result);

        } catch (Exception $e) {
            $error = [
                'processed' => $processedCount,
                'errors' => $errorCount + 1,
                'message' => 'Error processing ' . $source . ' leads: ' . $e->getMessage()
            ];

            return $this->response->setContentType('application/json')
                ->setStatusCode(500)
                ->setJSON($error);
        }
    }

    /**
     * Process a single lead
     */
    private function processSingleLead($leadData): void
    {

        // Get client information
        $client = (new ClientModel())->builder()
            ->select(['name', 'subdomain', 'client_hash'])
            ->where('client_hash', $leadData->client_id)
            ->get()
            ->getFirstRow();

        if (!$client) {
            throw new Exception('Client not found for lead ID: ' . $leadData->id);
        }

        // Parse the lead data using the appropriate parser
        $rawData = is_array($leadData->data) ? json_encode($leadData->data) : $leadData->data;
        $parsedLead = $this->leadProcessingService->processLead($rawData, $leadData->source);

        // Prepare data for lead response
        $responseData = $this->prepareLeadResponseData($parsedLead, $leadData, $client);

        // Set up database connection for tenant
        $database = $client->subdomain . "_" . $client->client_hash;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($database);

        // Get vehicle information if stock number is provided (ensure fresh lookup per lead)
        $vehicleData = $this->getVehicleData($responseData['stock_no']);

        // Save lead response
        $responseData['assignee'] = (new LeadResponseRepository())->setAssigneeByAutoAssignmentAlgorithm();
        $response = (new LeadResponseRepository())->create($responseData);

        if (!isset($response['success'])) {
            throw new Exception('LEAD_SAVE_ERROR');
        }

        // Send notifications
        $this->sendLeadNotifications($parsedLead, $leadData, $client, $vehicleData);

        // Update lead status to processed (switch back to main database)
        $this->updateRecord('SUCCESS', 1, $leadData->id);
    }

    /**
     * Prepare data for lead response
     */
    private function prepareLeadResponseData($parsedLead, $leadData, $client): array
    {
        $sourceConfig = $this->getSourceConfiguration($leadData->source);

        return [
            'first_name' => $parsedLead->firstName,
            'last_name' => $parsedLead->lastName,
            'phone' => Utility::formatPhoneNumber($parsedLead->phone),
            'email' => $parsedLead->email,
            'inventory_id' => null,
            'stock_no' => $leadData->retailer_id ?? '',
            'source' => $sourceConfig['source_display_name'],
            'status' => 'New',
            'other_data' => json_encode($parsedLead->additionalFields),
            'provider_name' => $sourceConfig['provider_name']
        ];
    }

    /**
     * Get vehicle data if stock number is provided (ensures no caching between leads)
     */
    private function getVehicleData(string $stockNo): array
    {
        if (empty($stockNo)) {
            return [];
        }

        // Create fresh repository instance to avoid caching between leads
        $inventory = new InventoryRepository();

        // Clear any potential caching in the repository
        if (method_exists($inventory, 'clearCache')) {
            $inventory->clearCache();
        }

        $vehicle = $inventory->findSingleInventoryByStockId($stockNo);

        // Ensure we return a fresh array, not a cached reference
        return $vehicle ? json_decode(json_encode($vehicle), true) : [];
    }

    /**
     * Send lead notifications
     */
    private function sendLeadNotifications($parsedLead, $leadData, $client, $vehicleData): void
    {
        $sourceConfig = $this->getSourceConfiguration($leadData->source);

        // Prepare lead data for email templates
        $emailData = [
            'leadinfo' => [
                'business_name' => $client->name,
                'creation_date' => $leadData->creation_time
            ],
            'customer' => [
                'customer_name' => $parsedLead->getFullName(),
                'customer_phone' => $parsedLead->phone,
                'customer_email' => $parsedLead->email
            ],
            'additional' => $parsedLead->additionalFields,
            'vehicle' => $vehicleData
        ];

        // Send regular email notification
        $this->sendEmailNotification($sourceConfig, $emailData, $client, $leadData);

        // Send ADF email notification
        $this->sendAdfNotification($sourceConfig, $parsedLead, $client, $leadData);
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification(array $sourceConfig, array $emailData, $client, $leadData): void
    {
        $configuration = new ConfigurationRepository();
        $emailConfigKey = $sourceConfig['email_config_key'];
        $customerEmail = $configuration->findConfigurationColumnsByKey($emailConfigKey, ['value']);

        if (!$customerEmail || empty($customerEmail->value)) {
            return;
        }

        $emailTemplate = $sourceConfig['email_template'];
        $body = is_file(APPPATH . 'Views/' . $emailTemplate)
            ? view($emailTemplate, $emailData)
            : view('emails/generic_lead.php', $emailData);

        foreach (explode(',', $customerEmail->value) as $clientEmail) {
            if (filter_var(trim($clientEmail), FILTER_VALIDATE_EMAIL)) {
                $this->sendEmail($clientEmail, $client, $leadData, $body, $emailData['customer']['customer_email']);
            }
        }
    }

    /**
     * Send ADF notification
     */
    private function sendAdfNotification(array $sourceConfig, $parsedLead, $client, $leadData): void
    {
        $configuration = new ConfigurationRepository();
        $adfConfigKey = $sourceConfig['adf_email_config_key'];
        $adfEmail = $configuration->findConfigurationColumnsByKey($adfConfigKey, ['value']);

        if (!$adfEmail || empty($adfEmail->value)) {
            return;
        }

        $adfData = [
            'first_name' => $parsedLead->firstName,
            'last_name' => $parsedLead->lastName,
            'phone' => $parsedLead->phone,
            'email' => $parsedLead->email
        ];

        foreach (explode(',', $adfEmail->value) as $email) {
            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                $adf = new AdfTemplate($adfData);
                $this->sendEmail(
                    trim($email),
                    $client,
                    $leadData,
                    $adf->mapper(),
                    getEnv('internal.email_address'),
                    false
                );
            }
        }
    }

    /**
     * Send email helper
     */
    private function sendEmail(string $recipient, $client, $leadData, string $body, string $replyTo = '', bool $isHtml = true): void
    {
        $emailFactory = service('channelService')->getInstance("internal")['email'];
        $emailFactory->init(
            getEnv('notification.smtp.host'),
            getEnv('notification.smtp.username'),
            getEnv('notification.smtp.password'),
            getEnv('notification.smtp.port')
        );

        $emailFactory->setSubject("{$client->name} - ZOP LEAD - {$leadData->full_name}");
        $emailFactory->setContent($body);

        if ($replyTo) {
            $emailFactory->setReplyTo($replyTo, $leadData->full_name);
        }

        $emailFactory->setSender(
            getEnv($isHtml ? 'fb.ads.sender.email' : 'internal.email_address'),
            getEnv($isHtml ? 'fb.ads.sender.name' : 'internal.name')
        );
        $emailFactory->setRecipient($recipient);
        $emailFactory->setHTML($isHtml);
        $emailFactory->send();
    }

    /**
     * Get source-specific configuration
     */
    private function getSourceConfiguration(string $source): array
    {
        $configurations = [
            'facebook' => [
                'provider_name' => 'Zop Media FB',
                'source_display_name' => 'Zop FB',
                'email_template' => 'emails/facebook_lead.php',
                'email_config_key' => 'facebook_ads_email',
                'adf_email_config_key' => 'facebook_ads_email_adf'
            ],
            'google' => [
                'provider_name' => 'Google Ads',
                'source_display_name' => 'Google Ads',
                'email_template' => 'emails/google_lead.php',
                'email_config_key' => 'google_ads_email',
                'adf_email_config_key' => 'google_ads_email_adf'
            ]
        ];

        return $configurations[$source] ?? $configurations['facebook'];
    }

    /**
     * Update lead record in main database (switches back from tenant DB)
     */
    private function updateRecord($message, $status, $id): void
    {
        // Ensure database connection is established
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase(getEnv('database.default.database'));

        $builder = (new \App\Models\LeadSourceModel())->builder()
            ->set('status', $status)
            ->set('message', $message)
            ->where('id', $id);
        $builder->update();
    }
}