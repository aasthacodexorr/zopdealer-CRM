<?php

namespace Tenant\app\Controllers;

use App\Composites\LeadTemplateFormComposite;
use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Libraries\KBB\KBB;
use App\Libraries\Mailbox\Mailbox;
use App\Libraries\Schedule\ScheduleConstant;
use App\Libraries\WorkFlow\WorkFlowConstant;
use Tenant\app\Repositories\LeadResponseRepository;
use Carbon\Carbon;
use Tenant\app\Libraries\Adf\AdfTemplate;
use Tenant\app\Libraries\Lead\LeadGRTemplate;
use Tenant\app\Libraries\Captcha\SimpleCaptcha;
use Tenant\app\Libraries\OneTimeAuthentication\OTP;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;
use Tenant\app\Libraries\StripePayment\PaymentContextState;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Libraries\Report\ReportFactory;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\LeadResponseFileRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\InventoryRepository;

/**
 * LeadResponseController : This controller captures the leads created by the customer of the clients (tenants)
 * or clients themselves. It corresponds to the API related to creating, managaing or eleted Leads as well as other
 * resources related tot the leads.
 */
class LeadResponseController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the LeadRepository which can be used by any method here.
     *
     * @var LeadResponseRepository
     */
    private $repository;

    /**
     * lead_file_repository
     *
     * @var LeadResponseFileRepository
     */
    private $lead_file_repository;

    /**
     * kbb_api
     *
     * @var KBB
     */
    private $kbb_api;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new LeadResponseRepository();
        $this->lead_file_repository = new LeadResponseFileRepository();
        $this->kbb_api = new KBB();
        $this->kbb_api->setAPI_KEY(getenv('KBB.API_KEY'));
        $this->kbb_api->setAPI_URL(getenv('KBB.API_URL'));
        $this->configuration_repository = new ConfigurationRepository();
    }

    /**
     * create This method creates a new lead. 
     * First the recapcha is validated of the incoming data, if the lead is created by the client, than
     * this step is skipped. Than the data is validated for its consistency against the corresponding template 
     * and finally they are saved in the database.
     *
     * @return void
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->logLeadImportantDetails($data);

        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('google.recaptcha.private'));

        $configurationRepository = new ConfigurationRepository();
        $kbb_enabled = $configurationRepository->findConfigurationColumnsByKey('enable_kelly_blue_book', ['value']);
        $kbb_enabled = $kbb_enabled->value;
        $kbb_enabled = intval($kbb_enabled);
        $otp_hash = "";
        $captcha_hash = "";

        if (!empty($data['otp']) && isset($data['hash'])) {

            if (!OTP::validate($data['hash'], $data['otp'])) {

                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => ['otp' => 'OTP is either expired or wrong. Please try again.']]
                )->send();
                exit;
            }

            $otp_hash = $data['hash'];
            unset($data['hash']);
            unset($data['otp']);
        } else if (!empty($data['captcha']) && isset($data['hash'])) {

            if (!SimpleCaptcha::validate($data['hash'], $data['captcha'])) {

                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => ['captcha' => 'Captcha is either expired or wrong. Please try again.']]
                )->send();
                exit;
            }

            $captcha_hash = $data['hash'];
            unset($data['hash']);
            unset($data['captcha']);
        } else if (!isset(service('authPolicy')->email)) {
            //Check if recapcahe is right
            $resp = $recaptcha->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => 'Please validate Recaptcha']
                )->send();
                exit;
            }
        }

        //Check if form_id is present 
        if (isset($data['form_id']) && is_numeric($data['form_id'])) {
            //If form_id is present get fields of that particular form

            $leadTemplateFormComposite = new LeadTemplateFormComposite();
            $form_data = $leadTemplateFormComposite->getElements($data['form_id']);
            $validation_rules = $leadTemplateFormComposite->validateElements();

            //Check if Lead has files.
            if ($this->request->getFiles()) {
                $files = $this->request->getFiles();
                foreach ($files as $file_key => $file_object) {

                    if (!array_key_exists($file_key, $validation_rules)) {
                        $validation_rules[$file_key] = [
                            'label' => ucwords(str_replace("_", " ", $file_key)),
                            'rules' => 'max_size[' . $file_key . ',131072]|ext_in[' . $file_key . ',png,jpg,pdf,jpeg,heif,heic]'
                        ];
                    }
                }
            }

            //Validate the fields 
            if (!$this->validate($validation_rules)) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => $this->validator->getErrors()]
                )->send();
                exit;
            } else {
                //Save the form details 
                $other_data = $data;

                $kbb_response = [];

                if (!empty($data['kbb_id']) && !empty($data['vehicle_condition_id']) && !empty($kbb_enabled)) {
                    $odometer = !empty($data['odometer']) ? intval($data['odometer']) : 0;
                    $kbb_response = $this->kbb_api->getVehiclePriceAdvisory(
                        KBB_DEALER_PRICE_TYPE,
                        $data['vehicle_condition_id'],
                        $data['kbb_id'],
                        (string) $odometer
                    );

                    if (empty($kbb_response['errors'])) {
                        $other_data = array_merge($other_data, $kbb_response);
                    }
                }

                $reset_columns_in_other_data = [
                    'g-recaptcha-response',
                    'form_id',
                    'first_name',
                    'last_name',
                    'phone',
                    'email',
                    'inventory_id',
                    'stock_no',
                    'source',
                    'status',
                    'assignee',
                    'zm_year',
                    'zm_make',
                    'zm_model',
                    'zm_trim',
                    'zm_image',
                    'zm_vin',
                    'vehicle_condition_id',
                    'kbb_id',
                    'model_id',
                    'make_id',
                    'trim_id'
                ];

                foreach ($reset_columns_in_other_data as $each_column) {
                    if (isset($other_data[$each_column])) {
                        unset($other_data[$each_column]);
                    }
                }

                $data['other_data'] = json_encode($other_data);
                $data['status'] = isset($data['status']) && $data['status'] != '' ? $data['status'] : 'New';
                $data['source'] = isset($data['source']) ? $data['source'] : '';
                $data['assignee'] = isset($data['assignee']) ? $data['assignee'] : '';

                if (empty($data['assignee'])) {
                    $data['assignee'] = $this->repository->setAssigneeByAutoAssignmentAlgorithm();
                }

                $response = $this->repository->create($data);

                if (isset($response['success'])) {

                    //Check if Lead has files.
                    if ($this->request->getFiles()) {
                        $files = $this->request->getFiles();
                        foreach ($files as $file_key => $file_object) {
                            if (empty($file_object->getError())) {
                                $lead_response_file = [];
                                $name = Utility::uniqueNumberBasedString("LEAD_");
                                $name = $name . $file_object->getRandomName();
                                CloudStorageFactory::getInstance()->save($file_object, UPLOAD_FOLDER . '/' . md5(db_connect()->database) . '/' . $name);
                                $lead_response_file['file_url'] = "/" . UPLOAD_FOLDER . "/" . md5(db_connect()->database) . "/" . $name;
                                $lead_response_file['header'] = $file_key;
                                $lead_response_file['lead_response_id'] = $response['lead_id'];

                                $this->lead_file_repository->create($lead_response_file);
                                $data[$file_key] = getenv('image.cdn') . $lead_response_file['file_url'];
                            }
                        }
                    }


                    //On Success Send Mail
                    //TODO: Need to Queue Mails In future.
                    //TODO: 
                    //Send mail if only submited from external source. 
                    $customer_name = isset($data['last_name']) ? $data['last_name'] : '';
                    $customer_name = isset($data['first_name']) ? $customer_name . ' ' . $data['first_name'] : $customer_name;
                    if (!isset(service('authPolicy')->email)) {
                        $configuration = new ConfigurationRepository();
                        $account_email_name = $configuration->findConfigurationColumnsByKey('account_email_name', ['value']);
                        $account_email_name = $account_email_name->value;

                        $account_email_address = $configuration->findConfigurationColumnsByKey('account_email_address', ['value']);
                        $account_email_address =  $account_email_address->value;

                        $account_email_template_header = $configuration->findConfigurationColumnsByKey('account_email_template_header', ['value']);
                        $account_email_template_header = $account_email_template_header->value;

                        $account_email_template_content = $configuration->findConfigurationColumnsByKey('account_email_template_content', ['value']);
                        $account_email_template_content = $account_email_template_content->value;

                        $account_email_template_footer = $configuration->findConfigurationColumnsByKey('account_email_template_footer', ['value']);
                        $account_email_template_footer = $account_email_template_footer->value;
                        //auto reply to client.
                        if ($form_data['auto_reply'] == '1') {
                            $client_mail = new Mailbox(true);
                            $client_mail->Subject = 'Thank You For Contacting Us.';
                            $client_mail->setFrom($account_email_address, $account_email_name);
                            $client_mail->Body = '';

                            if ($form_data['override_auto_reply_content'] == '1') {
                                $account_email_template_content = $form_data['auto_reply_content'];
                            }

                            if (is_file(APPPATH . 'Views/emails/' . $form_data['auto_reply_template'] . '.php')) {
                                $body = view(
                                    'emails/' . $form_data['auto_reply_template'],
                                    [
                                        'header' => $account_email_template_header,
                                        'content' => $account_email_template_content,
                                        'footer' => $account_email_template_footer

                                    ]
                                );

                                $client_mail->Body = $body;
                            }


                            $client_mail->addAddress($data['email'], $customer_name);

                            if (!empty($form_data['recipient_email'])) {
                                foreach (explode(',', $form_data['recipient_email']) as $admin_email_for_reply) {
                                    if (filter_var($admin_email_for_reply, FILTER_VALIDATE_EMAIL)) {
                                        $client_mail->addReplyTo($admin_email_for_reply);
                                    }
                                }
                            }

                            $client_mail->Send();
                        }


                        //send reply to the admins.
                        $admin_mail = new Mailbox(true);
                        $admin_mail->Subject = $customer_name . ' submitted a ' . $data['source'];
                        $admin_mail->setFrom($account_email_address, $account_email_name);
                        $admin_mail->addReplyTo($data['email'], $customer_name);
                        $admin_mail->Body = '';

                        if (is_file(APPPATH . 'Views/emails/' . $form_data['admin_reply_template'] . '.php')) {
                            $body = view('emails/' . $form_data['admin_reply_template'], $data);

                            $admin_mail->Body = $body;
                        }

                        if (!empty($form_data['recipient_email'])) {
                            $admin_email_set = false;
                            foreach (explode(',', $form_data['recipient_email']) as $admin_email) {
                                if (filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
                                    $admin_email_set = true;
                                    $admin_mail->addAddress($admin_email, $account_email_name);
                                }
                            }

                            if ($admin_email_set) {
                                $admin_mail->Send();
                            }
                        }

                        //Send SMS if recipient phone is set
                        if (!empty($form_data['recipient_phone'])) {
                            foreach (explode(',', $form_data['recipient_phone']) as $recipient_phone) {
                                if (preg_match('/^[0-9]{10}+$/', $recipient_phone)) {
                                    try {
                                        $smsContent = 'You Have Received A New Lead. ';
                                        $smsContent .= 'Name : ';
                                        $smsContent .= $customer_name;
                                        $smsContent .= ' Email : ';
                                        $smsContent .= $data['email'];
                                        $smsContent .= ' Phone : ';
                                        $smsContent .= $data['phone'];
                                        $smsContent .= ' Thx Zop';

                                        $smsFactory = service('channelService')->getInstance("internal")['sms'];
                                        $smsFactory->setSid(getenv('twilio.sid'));
                                        $smsFactory->setAuth_token(getenv('twilio.auth_token'));
                                        $smsFactory->setSender(getenv('twilio.phone'));
                                        $smsFactory->setContent($smsContent);
                                        $smsFactory->setRecipient('+1' . $recipient_phone);
                                        $smsFactory->send();
                                    } catch (\Exception $ex) {
                                    }
                                }
                            }
                        }

                        if (!empty($form_data['recipient_adf_email'])) {
                            foreach (explode(',', $form_data['recipient_adf_email']) as $adf_email) {
                                if (filter_var($adf_email, FILTER_VALIDATE_EMAIL)) {

                                    try {
                                        $adf = new AdfTemplate($data);
                                        $emailFactory = service('channelService')->getInstance("internal")['email'];
                                        $emailFactory->init(
                                            getEnv('notification.smtp.host'),
                                            getEnv('notification.smtp.username'),
                                            getEnv('notification.smtp.password'),
                                            getEnv('notification.smtp.port')
                                        );
                                        $emailFactory->setSubject('You Have Received A New Lead.');
                                        $emailFactory->setContent($adf->mapper());
                                        $emailFactory->setSender(getEnv('internal.email_address'), getEnv('internal.name'));
                                        $emailFactory->setRecipient($adf_email);
                                        $emailFactory->setHTML(false);
                                        $emailFactory->send();
                                    } catch (\Exception $ex) {
                                    }
                                }
                            }
                        }

                        $this->notifyDealership("You have received a new lead.");
                    }

                    if (!empty($kbb_response['price_advisory'])) {
                        $response['price_advisory'] = $kbb_response['price_advisory'];
                    }

                    if (!empty($response['lead_id'])) {

                        $default_deposit_amount = (new ConfigurationRepository())->findConfigurationColumnsByKey('default_deposit_amount', ['value']);
                        $default_deposit_amount = floatval(abs($default_deposit_amount->value));

                        if (!empty($default_deposit_amount)) {
                            $response["payment_url"] = (new PaymentContextState())->encrypt($default_deposit_amount, MODULE_LEAD, $response['lead_id'], true, ['reason' => 'Deposit']);
                        }
                    }

                    if (!empty($otp_hash)) {
                        OTP::invalidate($otp_hash);
                    }

                    if (!empty($captcha_hash)) {
                        SimpleCaptcha::invalidate($captcha_hash);
                    }

                    service('workFlowService')->attach(WorkFlowConstant::CREATE, ['module' => WorkFlowConstant::LEAD, 'module_id' => $response['lead_id']]);
                    $this->repository->updateSearchIndex([$response['lead_id']]);

                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                ['errors' => 'Need Valid Form Id']
            )->send();
        }
    }

    /**
     * show This simply shows the complete lead form saved by the user or the client.
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        $response = $this->repository->show($id);

        $this->existenceOrRightsCheckForLead($response);

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Lead couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $available_lead = $this->repository->show($id);
        $this->existenceOrRightsCheckForLead($available_lead);

        //Check if form_id is present 
        if (isset($data['form_id']) && is_numeric($data['form_id'])) {

            $leadTemplateFormComposite = new LeadTemplateFormComposite();
            $form_data = $leadTemplateFormComposite->getElements($data['form_id']);
            if (!$this->validate($leadTemplateFormComposite->validateElements())) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => $this->validator->getErrors()]
                )->send();
                exit;
            } else {
                $response = $this->repository->update($id, $data);

                if (isset($response['success'])) {

                    service('workFlowService')->attach(WorkFlowConstant::UPDATE, ['module' => WorkFlowConstant::LEAD, 'module_id' => $id]);
                    $this->repository->updateSearchIndex([$id]);
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                ['errors' => 'Need Valid Form Id']
            )->send();
        }
    }

    public function partialUpdate()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'lead_id' => [
                'label' => 'Lead ID',
                'rules' => 'required',
            ],
            'column' => [
                'label' => 'Column',
                'rules' => 'required',
            ],
            'value' => [
                'label' => 'Value',
                'rules' => 'permit_empty',
            ],
        ]);

        if ($validated) {
            $available_lead = $this->repository->show($data['lead_id']);
            $this->existenceOrRightsCheckForLead($available_lead);

            $lead = [];
            $lead[$data['column']] = $data['value'];
            $response = $this->repository->update($data['lead_id'], $lead);

            if (isset($response['success'])) {

                service('workFlowService')->attach(WorkFlowConstant::UPDATE, ['module' => WorkFlowConstant::LEAD, 'module_id' => $data['lead_id']]);
                $this->repository->updateSearchIndex([$data['lead_id']]);
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }


    public function updates()
    {

        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("lead_response_ids", $columns)) {
            unset($columns['lead_response_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $data['lead_response_ids'] = explode(',', $data['lead_response_ids']);

        if (count($data['lead_response_ids']) > 0 && count($columns) > 0) {
            $this->repository->multipleUpdates($data['lead_response_ids'], $columns);
            $this->repository->updateSearchIndex($data['lead_response_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    /**
     * multipleDeletes Delete multiple rows of data. Requires array of $lead_response_ids
     *
     * @return void
     */
    public function multipleDeletes()
    {
        $data = $this->request->getPost();

        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "You do not have enough privelages to delete the leads."
            ])->send();
            exit;
        }

        $data['lead_response_ids'] = explode(',', $data['lead_response_ids']);

        if (count($data['lead_response_ids']) > 0) {
            $this->repository->multipleDeletes($data['lead_response_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }

    /**
     * noteCreate This creates a new note corresponding to the lead along with an attachment.
     *
     * @param  int $id This is the Lead Id for which the note is supposed to be created.
     * @return void
     */
    public function noteCreate($id)
    {

        $lead = $this->repository->show($id);
        $this->existenceOrRightsCheckForLead($lead);


        $data = $this->request->getPost();
        $validated = true;
        $response = [];

        if (!array_key_exists('attachment', $this->request->getFiles())) {
            //save details
            $response = $this->repository->saveNote($id, $data);
        } else {
            $validated = $this->validate([
                'attachment' => [
                    'uploaded[attachment]',
                    'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[attachment,32000]',
                ],
            ]);

            if ($validated) {
                //save file
                $file = $this->request->getFile('attachment');
                $name = $file->getRandomName();

                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                $data['attachment'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;

                //save details
                $response = $this->repository->saveNote($id, $data);
            } else {

                //throw error
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
                exit;
            }
        }

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * showNote This method accepts two paramters lead id as well as id of the note.
     * Lead id is simply used for routing purpose.
     *
     * @param  mixed $lead_id
     * @param  mixed $id
     * @return void
     */
    public function showNote($lead_id, $id)
    {
        $lead = $this->repository->show($lead_id);

        $this->existenceOrRightsCheckForLead($lead);

        $response = $this->repository->showNote($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * allNotes This method shows the list of all the notes related to a particular lead.
     *
     * @param  mixed $lead_id Lead Response Id.
     * @return void
     */
    public function allNotes($lead_id)
    {

        $lead = $this->repository->show($lead_id);

        $this->existenceOrRightsCheckForLead($lead);

        $response = $this->repository->allNotes($lead_id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * updateNote This method allows the user to update the note, if he is note owner or tenant owner or superowner.
     *
     * @param  int $lead_id Lead Response Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function updateNote($lead_id, $id)
    {
        $lead = $this->repository->show($lead_id);

        $this->existenceOrRightsCheckForLead($lead);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to update lead note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Lead note could not be found.'])->send();
            exit;
        }

        $data = $this->request->getPost();
        $response = $this->repository->updateNote($id, $data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * deleteNote This method allows the user to delete the note, if he is note owner or tenant owner or superowner.
     *
     * @param  int $lead_id Lead Response Id.
     * @param  int $id Note Id to be updated.
     * @return void
     */
    public function deleteNote($lead_id, $id)
    {
        $lead = $this->repository->show($lead_id);

        $this->existenceOrRightsCheckForLead($lead);

        $note = $this->repository->showNote($id);
        if (!empty($note)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($note['user_id']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have permission to delete lead note.'])->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Lead note could not be found.'])->send();
            exit;
        }
        $response = $this->repository->deleteNote($id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * allFiles This retrieves all the files for a particular lead.
     *
     * @param  mixed $lead_id
     * @return void
     */
    public function allFiles($lead_id)
    {

        $lead = $this->repository->show($lead_id);

        $this->existenceOrRightsCheckForLead($lead);

        $response = $this->lead_file_repository->filterFilesByLeadId($lead_id);

        foreach ($response as $key => $single_response_object) {
            $response[$key]['file_url'] = Utility::getCdnUrl() . $response[$key]['file_url'];
        }
        $this->response->setContentType('application/json')->setJSON(['files' => $response])->send();
    }

    public function filters()
    {
        $query = [];
        $result = [];
        $status = false;
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        $items = 10;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('status')) {
            $status = $this->request->getGet('status') == "true" ? true : false;
        }

        $result = $this->repository->allColumnAggregation($query, $status);

        $this->response->setContentType('application/json')->setJSON($result)->send();
    }

    public function index()
    {
        $query = [];
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        $items = 100;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->repository;

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->exactSearch($search);
            }
        }

        if ($this->request->getGet('only_current_month')) {
            if ($this->request->getGet('only_current_month') == "true") {
                $data->filterOnCurrentMonthAndYear("created_at");
            }
        }

        if (!$this->isCurrentUserAuthorizedToViewAllLeads()) {
            $data->filterData("assignee", service('authPolicy')->id);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'lead_response_id', 'lead_response_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function convertLead($id)
    {
        $lead = $this->repository->show($id);

        $this->existenceOrRightsCheckForLead($lead);

        $map_lead_to_customer = [
            'drivers_license_number' => 'driver_licence',
            'home_address' => 'street_address',
            'mobile_phone' => 'mobile',
            'phone' => 'mobile',
            'driving_licence' => 'driver_licence',
            'address_1' => 'street_address',
            'home_phone' => 'phone',
        ];

        if (!empty($lead['other_data'])) {
            $lead = array_merge($lead, json_decode($lead['other_data'], true));
        }

        foreach ($map_lead_to_customer as $key => $value) {
            if (isset($lead[$key])) {
                $lead[$value] = $lead[$key];
                unset($lead[$key]);
            }
        }

        if (isset($lead['date_of_birth']) && strtotime($lead['date_of_birth']) == true) {
            $date_of_birth = Carbon::parse($lead['date_of_birth']);
            $lead['date_of_birth'] = $date_of_birth->format('m/d/Y');
        }

        if (isset($lead['birthdate']) && strtotime($lead['birthdate']) == true) {
            $date_of_birth = Carbon::parse($lead['birthdate']);
            $lead['date_of_birth'] = $date_of_birth->format('m/d/Y');
        }

        if (isset($lead['drivers_license_expiry_date']) && strtotime($lead['drivers_license_expiry_date']) == true) {
            $drivers_license_expiry_date = Carbon::parse($lead['drivers_license_expiry_date']);
            $lead['driver_licence_expiry_date'] = $drivers_license_expiry_date->format('m/d/Y');
        }

        if (isset($lead['driving_licence_expiry_date']) && strtotime($lead['driving_licence_expiry_date']) == true) {
            $drivers_license_expiry_date = Carbon::parse($lead['driving_licence_expiry_date']);
            $lead['driver_licence_expiry_date'] = $drivers_license_expiry_date->format('m/d/Y');
        }

        if (isset($lead['driver_licence_expiry_date']) && strtotime($lead['driver_licence_expiry_date']) == true) {
            $driver_licence_expiry_date = Carbon::parse($lead['driver_licence_expiry_date']);
            $lead['driver_licence_expiry_date'] = $driver_licence_expiry_date->format('m/d/Y');
        }

        if (isset($lead['previous_bankruptcy']) && $lead['previous_bankruptcy'] == "Yes") {
            $lead['bankruptcy_declared'] = "1";
        }

        $customer_repository = new CustomerRepository();

        unset($lead['id']);
        unset($lead['is_encrypted']);
        unset($lead['created_at']);
        unset($lead['updated_at']);

        $customer = $customer_repository->create($lead);
        if (isset($customer['success'])) {
            $map_credit_application_from_lead = [
                'marital_status' => 'marital_status',
                'sin' => 'sin',
                //'residence_ownership' => 'residence_status',
                'market_value' => 'market_value',
                'mortgage_amount' => 'mortgage_balance_amount',
                'monthly_payment'   => 'mortgage_rent_amount_monthly',
                'years_at_present_address'  => 'residence_period_years',
                'employer_name' => 'employer_name',
                'job_title' => 'job_title',
                'employer_work_phone' => 'employer_work_phone',
                'employment_period_years' => 'employment_period_years',
                'employment_period_months' => 'employment_period_months',
                'employer_address' => 'employer_address',
                'employer_city' => 'employer_city',
                'employer_province' => 'employer_province',
                'employer_postal_code' => 'employer_postal_code',
                'monthly_gross' => 'gross_income',
                'previous_job_title' => 'previous_job_title',
                'previous_employer_name' => 'previous_employer_name',
                'previous_employer_work_phone' => 'previous_work_phone',
                'previous_employment_period_years' => 'previous_employment_period_years',
                'previous_employment_period_months' => 'previous_employment_period_months',
                'other_income_source' => 'other_income_source',
                'other_monthly_gross' => 'other_gross_income',

                'previous_address' => 'previous_address',
                'previous_address_city' => 'previous_address_city',
                'previous_address_province' => 'previous_address_province',
                'previous_address_postal_code' => 'previous_address_postal_code',
                'previous_residence_address_period_years' => 'previous_residence_address_period_years',
                'previous_residence_address_period_years' => 'previous_residence_period_years',
                'previous_residence_period_months' => 'previous_residence_period_months',
                'previous_residence_monthly_payment' => 'previous_residence_monthly_payment',

            ];

            if (isset($customer['customer_id'])) {
                foreach ($map_credit_application_from_lead as $key => $value) {
                    if (isset($lead[$value])) {
                        $lead[$key] = preg_replace('/[^ \w-]/', '', $lead[$value]);
                        $lead[$key] = preg_replace('/\s+/S', " ", $lead[$value]);
                    }
                }

                if (isset($lead['residence_status'])) {
                    $lead['residence_status'] == 'Own' ||
                        $lead['residence_status'] == 'Own Free Clear' ||
                        $lead['residence_status'] == 'With Family' ? $lead['residence_ownership'] = 'Own' : null;

                    $lead['residence_status'] == 'Rent' ? $lead['residence_ownership'] = 'Rent' : null;
                }

                $lead['customer_id'] = $customer['customer_id'];

                (new CustomerCreditApplicationRepository())->create($lead);

                if (isset($lead['coapplicant']) && $lead['coapplicant'] == "Yes") {
                    $co_applicant_lead = [];

                    $mapping_co_applicant_lead = [
                        'first_name' => 'coapplicant_first_name',
                        'last_name'  => 'coapplicant_last_name',
                        'email'      => 'coapplicant_email',
                        'phone'      => 'coapplicant_phone',
                        'mobile'     => 'coapplicant_mobile_phone',
                        'work_phone' => 'coapplicant_work_phone',
                        'sin'        => 'coapplicant_sin',
                        //'date_of_birth'  => 'coapplicant_date_of_birth',
                        'marital_status' => 'coapplicant_marital_status',
                        'market_value' => 'coapplicant_market_value',
                        'mortgage_amount' => 'coapplicant_mortgage_balance_amount',
                        'driver_licence' => 'coapplicant_drivers_license_number',

                        'street_address' => 'coapplicant_home_address',
                        'city'       => 'coapplicant_city',
                        'province'   => 'coapplicant_province',
                        'postal_code'    => 'coapplicant_postal_code',
                        //'residence_ownership' => 'coapplicant_residence_status',
                        'monthly_payment' => 'coapplicant_mortgage_rent_amount_monthly',
                        'residence_period_years' => 'coapplicant_residence_period_years',
                        'years_at_present_address' => 'coapplicant_residence_period_years',

                        'employer_name' => 'coapplicant_employer_name',
                        'job_title' => 'coapplicant_job_title',
                        'employer_work_phone' => 'coapplicant_employer_work_phone',
                        'employer_work_phone'    => 'coapplicant_employer_work_phone',
                        'monthly_gross'  => 'coapplicant_gross_income',
                        'employment_period_years' => 'coapplicant_employment_period_years',
                        'employment_period_months'   => 'coapplicant_employment_period_months',

                        // 'bankruptcy_declared' => 'coapplicant_previous_bankruptcy'
                    ];

                    if (isset($lead['coapplicant_residence_status'])) {
                        $lead['coapplicant_residence_status'] == 'Own' ||
                            $lead['coapplicant_residence_status'] == 'Own Free Clear' ||
                            $lead['coapplicant_residence_status'] == 'With Family' ? $co_applicant_lead['residence_ownership'] = 'Own' : null;

                        $lead['coapplicant_residence_status'] == 'Rent' ? $co_applicant_lead['residence_ownership'] = 'Rent' : null;
                    }

                    if (isset($lead['coapplicant_date_of_birth']) && strtotime($lead['coapplicant_date_of_birth']) == true) {
                        $date_of_birth = Carbon::parse($lead['coapplicant_date_of_birth']);
                        $co_applicant_lead['date_of_birth'] = $date_of_birth->format('m/d/Y');
                    }

                    if (isset($lead['coapplicant_previous_bankruptcy']) && $lead['coapplicant_previous_bankruptcy'] == "Yes") {
                        $co_applicant_lead['bankruptcy_declared'] = "1";
                    }


                    foreach ($mapping_co_applicant_lead as $key => $value) {
                        if (isset($lead[$value])) {
                            $co_applicant_lead[$key] = preg_replace('/[^ \w-]/', '', $lead[$value]);
                            $co_applicant_lead[$key] = preg_replace('/\s+/S', " ", $lead[$value]);
                            $co_applicant_lead[$key] = $lead[$value];
                        }
                    }

                    $co_applicant_customer = $customer_repository->create($co_applicant_lead);
                    if (isset($co_applicant_customer['customer_id'])) {
                        $co_applicant_lead['customer_id'] = $co_applicant_customer['customer_id'];
                        (new CustomerCreditApplicationRepository())->create($co_applicant_lead);
                    }
                }
            }

            $this->repository->setConvertFlagForLead($id, 1);
            $this->response->setContentType('application/json')->setJSON($customer)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($customer)->send();
        }
    }


    public function generatePDF($bill_type, $id)
    {
        $lead = $this->repository->show($id);

        $this->existenceOrRightsCheckForLead($lead);

        if ($lead == null || !isset($lead['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Customer details couldn't be found."
            ])->send();
            exit;
        }

        try {
            $response['customers'][0] = $lead;
            $response['customers'][0]['deal_customer_type'] = 'primary_customer';
            $leadWithInventory = $this->addInventoryData($lead);
            if (!empty($leadWithInventory['inventory']) && (is_array($leadWithInventory['inventory']))) {
                $response['inventory'][0] = (array) $leadWithInventory['inventory'];
            }

            $response['location'] = (new LocationRepository())->getLocationByName("");

            $test_drive_template = ($this->configuration_repository->findConfigurationColumnsByKey('test_drive_template', ['value']))->value;
            $pdf = PDFFillerFactory::getInstance($test_drive_template, PDFFillerConstant::TEST_DRIVE);

            $this->response->setHeader('Content-Type', 'application/pdf');
            $pdf->dataMap($response)->output();
        } catch (\Error $error) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Couldn't generate the Test Driving form."])->send();
            exit;
        }
    }

    public function reportIndex()
    {

        if (service('authPolicy')->hasElevatedRoles()) {

            $items = 100;
            $paginate = true;

            $column = 'created_at';
            $order = 'DESC';

            $query = null;

            if (count($_GET) > 0) {
                $query = $this->repository->prepareDataForFilter($_GET);
            }

            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }

            if ($this->request->getGet('page_no')) {
                $_GET['page'] = $this->request->getGet('page_no');
            }

            if ($this->request->getGet('paginate')) {
                $paginate = $this->request->getGet('paginate');
                $paginate  = $paginate == 'false' ? false : true;
            }

            if ($this->request->getGet('column')) {
                $column = $this->request->getGet('column');
            }

            if ($this->request->getGet('order')) {
                $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
            }


            $data = $this->repository;

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'lead_view_id', 'lead_listing');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function generateReport($report_type)
    {
        if (service('authPolicy')->hasElevatedRoles() && $this->request->getPost('view')) {

            $items = 100;
            $paginate = false;

            $column = 'created_at';
            $order = 'DESC';

            $view = null;

            $search = NULL;

            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }

            if ($this->request->getGet('page_no')) {
                $_GET['page'] = $this->request->getGet('page_no');
            }

            if ($this->request->getGet('column')) {
                $column = $this->request->getGet('column');
            }

            if ($this->request->getGet('order')) {
                $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
            }

            if ($this->request->getPost('view')) {
                $view = $this->request->getPost('view');
            }

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
            }

            $reportFactory = (new ReportFactory())->getInstance(ScheduleConstant::LEAD_REPORT);
            $reportFactory->setDataFromEntityRepository($view, $_GET, $search, $column, $order, $items, $paginate)
                ->output('php://output', strtoupper($report_type));

            $this->response->setHeader('Content-Type', $reportFactory->getContentTypeHeaderBasedOnReportType(strtoupper($report_type)));
            die;
        } elseif (!$this->request->getPost('view')) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'View Parameter Missing.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    /**
     * notifyDealership
     *
     * @param  mixed $content
     * @return void
     */
    private function notifyDealership($content)
    {
        $pushFactory = service('channelService')->getInstance("internal")['push'];
        $pushFactory->setSubject("LEAD:RECEIVED");
        $pushFactory->setContent($content);

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }


    /**
     * existenceOrRightsCheckForLead This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForLead($lead)
    {
        if ($lead == null || !isset($lead['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Lead couldn't be found."
            ])->send();
            exit;
        }

        if (!$this->isCurrentUserAuthorizedToViewCurrentLead($lead)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the lead."])->send();
            exit;
        }
    }

    private function isCurrentUserAuthorizedToViewCurrentLead($lead)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_leads =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_leads', 'value')->value;
        $authorize_staff_to_view_all_leads =  !empty($authorize_staff_to_view_all_leads);

        if ($authorize_staff_to_view_all_leads) {
            return true;
        }

        return $lead['assignee'] == service('authPolicy')->id;
    }

    private function isCurrentUserAuthorizedToViewAllLeads()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_leads =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_leads', 'value')->value;
        return !empty($authorize_staff_to_view_all_leads);
    }

    public function send()
    {
        $data = $this->request->getPost();
        $rpaToken = (new ConfigurationRepository())->findConfigurationColumnsByKey('lead_rpa_token', 'value');

        if (!$this->isValidToken($data, $rpaToken)) {
            return $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }

        $lastLeadId = (new ConfigurationRepository())->findConfigurationColumnsByKey('last_lead_id', ['value']);
        $response = $this->repository->scheduleLeadOnCall('id', $lastLeadId->value);

        if (!$response) {
            return;
        }

        $responseInventory = $this->addInventoryData($response);
        $leadModified = (new LeadGRTemplate())->formatWithTemplate($response);
        // maintain calling order of code
        $leadModified['inventory'] = $responseInventory['inventory'];
        (new ConfigurationRepository())->setConfiguration(['last_lead_id' => $response['id']]);

        $this->response->setContentType('application/json')->setJSON($leadModified)->send();
    }

    private function addInventoryData(array $response)
    {
        $response['inventory'] = '';
        if ($response['stock_no'] != "") {
            //$response['inventory'] = (new InventoryRepository())->show($response['inventory_id']);
            $response['inventory'] = (new InventoryRepository())->findSingleInventoryByStockId($response['stock_no']);
        }
        return $response;
    }

    private function isValidToken(array $data, $rpaToken)
    {
        return !empty($data['token']) && $data['token'] === $rpaToken->value;
    }

    private function logLeadImportantDetails(array $data): void
    {

        $log_data = [];

        try {

            $attributes = [
                'first_name',
                'last_name',
                'phone',
                'email',
                'inventory_id',
                'stock_no',
                'source',
                'status',
                'assignee',
                'zm_year',
                'zm_make',
                'zm_model',
                'zm_trim',
                'zm_image',
                'zm_vin',
                'vehicle_condition_id',
                'kbb_id',
                'model_id',
                'make_id',
                'trim_id'
            ];

            foreach ($attributes as $attribute) {
                if (!empty($data[$attribute])) {
                    $log_data[$attribute] = $data[$attribute];
                }
            }

            log_message('info', "LEAD : " . json_encode($log_data));
        } catch (\Exception $ex) {
            log_message('info', "LEAD : " . json_encode($log_data));
        }
    }
}
