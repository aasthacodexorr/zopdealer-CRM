<?php
/**
 * AdfleadController handles the processing of leads.
 *
 * This controller is responsible for fetching leads, processing them, validating the data,
 * and saving the processed leads to the database. It also handles error logging and updating
 * the status of leads.
 *
 */

namespace App\Controllers;

use App\Helpers\Adflead;
use App\Repositories\AdfleadRepository;
use App\Composites\LeadTemplateFormComposite;
use App\Models\ClientModel;
use App\Models\AdfleadModel;
use Tenant\app\Repositories\LeadResponseRepository;

class AdfleadController extends BaseController
{
    /**
     * @var int $formid Static value for leads Forms
     * @var \CodeIgniter\Database\BaseConnection $dbase Database connection instance
     */

    private $formid = 2;  // @todo Static value for leads Forms, change to slug
    private $dbase;

    /**
     * Processes the leads.
     *
     * Fetches the lead list, processes each lead, and handles exceptions.
     *
     * @return void
     */
    public function process(): void
    {
        $config = $this->getLeadList();
        if (empty($config))
            return;

        foreach ($config as $dataObj) {
            try {
                $this->processLead($dataObj);
                echo "Process completed Successfully";
            } catch (\Exception $e) {
                $this->updateLeadStatus($dataObj->id, '2', $e->getMessage());
                log_message('error', $e->getMessage());
            }
        }
    }

    /**
     * Retrieves the list of leads to be processed.
     *
     * @return array List of leads
     */
    private function getLeadList()
    {
        return (new AdfleadRepository())->addSelect()->filterByStatus(0)->getLeadList();
    }

    /**
     * Processes a single lead.
     *
     * @param object $dataObj Lead data object
     * @return void
     * @throws \Exception If there is an error processing the lead
     */
    private function processLead($dataObj)
    {
        $xdata = Adflead::badFilter($dataObj->xdata);
        $xdata = Adflead::cleanAdfXml($xdata);
        $xml = $this->loadXml($xdata, $dataObj->id);
        // check if xml is valid
        if ($xml === false):
            //foreach (libxml_get_errors() as $error) {
            //    echo "\t", $error->message;
            //  }
            throw new \Exception("Failed loading XML For Record");
        endif;

        // check if any client exists with the email
        $client = $this->getClient($dataObj->xto);
        if (!$client)
            throw new \Exception("Failed: No Client in Record");

        // prepare lead data
        $data = $this->prepareLeadData($xml, $client);
        $this->dbase = $this->connectToClientDatabase($client);
        $leadTemplateFormComposite = new LeadTemplateFormComposite();
        $validation = \Config\Services::validation();

        if (!$this->validateLeadData($validation, $leadTemplateFormComposite, $data)) {
            throw new \Exception("Validation failed for lead data: " . json_encode($validation->getErrors()));
        } else {
            $status = $this->saveLeadData($data, $dataObj->id);
            if (!$status) {
                throw new \Exception("Failed : To save lead data");
            }
        }
    }

    /**
     * Loads XML data from a string.
     *
     * @param string $xdata XML data as string
     * @param int $recordId Record ID for error logging
     * @return \SimpleXMLElement|false Loaded XML object or false on failure
     */
    private function loadXml($xdata, $recordId)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xdata, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            //throw new \Exception("Failed loading XML For Record {$recordId}");
            // foreach (libxml_get_errors() as $error) {
            //    echo "\t", $error->message;
            //  }
        }
        return $xml;
    }

    /**
     * Retrieves client information based on email.
     *
     * @param string $email Client email
     * @return object|null Client information or null if not found
     */
    private function getClient($email)
    {
        return (new ClientModel())->builder()
            ->select(['subdomain', 'client_hash'])
            ->where('adf_email', $email)
            ->get()
            ->getFirstRow();
    }

    /**
     * Prepares lead data from XML and client information.
     *
     * @param \SimpleXMLElement $xml XML data
     * @param object $client Client information
     * @return array Prepared lead data
     */
    private function prepareLeadData($xml, $client)
    {
        $data = [
            'form_id' => $this->formid,
            'full_name' => Adflead::getName($xml)['firstname'] ?? 'Unknown' . " " . Adflead::getName($xml)['lastname'] ?? 'Unknown',
            'first_name' => Adflead::getName($xml)['firstname'] ?? 'Unknown',
            'last_name' => Adflead::getName($xml)['lastname'] ?? 'Unknown',
            'phone' => Adflead::getPhone($xml),
            'email' => trim(Adflead::getEmail($xml)),
            'stock_no' => (string)$xml->prospect->vehicle->stock ?? "",
            'source' => (string)$xml->prospect->provider->name ?? "",
            'zm_year' => (string)$xml->prospect->vehicle->year,
            'zm_make' => (string)$xml->prospect->vehicle->make,
            'zm_model' => (string)$xml->prospect->vehicle->model,
            'zm_vin' => (string)$xml->prospect->vehicle->vin ?? "",
            'status' => 'New',
            'other_data' => json_encode($this->prepareOtherData($xml))
        ];
        return $data;
    }

    /**
     * Prepares additional data from XML.
     *
     * @param \SimpleXMLElement $xml XML data
     * @return array Prepared additional data
     */
    private function prepareOtherData($xml): array
    {
        return [
            'price' => (string)$xml->prospect->vehicle->price ?? "",
            'comments' => (string)$xml->prospect->customer->comments ?? "",
            'vehicle' => (string)$xml->prospect->vehicle->year . " " . (string)$xml->prospect->vehicle->make . " " . (string)$xml->prospect->vehicle->model,
            'vin' => (string)$xml->prospect->vehicle->vin ?? ""
        ];
    }

    /**
     * Connects to the client's database.
     *
     * @param object $client Client information
     * @return \CodeIgniter\Database\BaseConnection Database connection instance
     */
    private function connectToClientDatabase($client)
    {
        $database = $client->subdomain . "_" . $client->client_hash;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($database);
        return $this->dbase;
    }

    /**
     * Validates the lead data.
     *
     * @param \CodeIgniter\Validation\Validation $validation Validation service
     * @param LeadTemplateFormComposite $leadTemplateFormComposite Lead template form composite
     * @param array $data Lead data
     * @return bool True if validation passes, false otherwise
     */
    private function validateLeadData($validation, $leadTemplateFormComposite, $data)
    {
        $leadTemplateFormComposite->getElements($this->formid);
        return $validation->setRules($leadTemplateFormComposite->validateElements())->run($data);
    }

    /**
     * Updates the status of a lead.
     *
     * @param int $leadId Lead ID
     * @param string $status New status
     * @param string $message Status message
     * @return void
     */
    private function updateLeadStatus($leadId, $status, $message): void
    {
        $this->resetToMainDatabase();
        $builder = (new AdfleadModel())->builder()
            ->set('status', $status)
            ->set('message', $message)
            ->where('id', $leadId);
        $builder->update();
    }

    /**
     * Saves the lead data to the database.
     *
     * @param array $data Lead data
     * @param int $leadId Lead ID
     * @return bool True if save is successful, false otherwise
     */
    private function saveLeadData($data, $leadId): bool
    {
        $status = false;
        $data['assignee'] = (new LeadResponseRepository)->setAssigneeByAutoAssignmentAlgorithm();
        $response = (new LeadResponseRepository)->create($data);

        // Switch/Reset Back to mainDB
        $this->resetToMainDatabase();

        if (isset($response['success'])) {
            $this->updateLeadStatus($leadId, '1', '');
            $status = true;
        }
        return $status;
    }

    /**
     * Resets the database connection to the main database.
     *
     * @return void
     */
    private function resetToMainDatabase(): void
    {
        $this->dbase->setDatabase(getenv('database.default.database'));
    }
}