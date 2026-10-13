<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Tenant\app\Libraries\ExternalReview\ExternalReviewFactory;
use Tenant\app\Libraries\ExternalReview\ReviewSource;
use Tenant\app\Libraries\Shortner\ZopUrlShortner;
use Tenant\app\Models\ReviewFeedbackListModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ReviewFeedbackRepository;

class ReviewFeedbackController extends \App\Controllers\BaseController
{
    private $repository;

    private $customer_repository;

    private $configuration_repository;

    private $location_repository;

    private $external_review_factory;

    public function __construct()
    {
        $this->repository = new ReviewFeedbackRepository();
        $this->customer_repository = new CustomerRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->location_repository = new LocationRepository();
        $this->external_review_factory = new ExternalReviewFactory();
    }

    public function index()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $items = 100;
            $paginate = true;

            $column = 'created_at';
            $order = 'DESC';

            $query = null;

            $this->repository->setModel(new ReviewFeedbackListModel());

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

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
                if ($search != '') {
                    $data = $this->repository->exactSearch($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'review_feedback_view_id', 'review_feedback_listing');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function create($customer_id)
    {

        $data = $this->request->getPost();

        $validated = $this->validate([
            'request_review_methods' => [
                "label" => 'Feedback Channel',
                "rules" => 'required'
            ],
        ]);


        if ($validated) {
            $this->customerExist($customer_id);
            $encrypted_id = Utility::shortEncryptString(md5(db_connect()->database) . "-" . $customer_id, getenv("password.encryption"));

            $review_url = "https://" . $_SERVER['HTTP_HOST'] . "/api/website/reviews/feedback/render/" . $encrypted_id;

            $short_review_url = ZopUrlShortner::getShortUrl($review_url);

            $customer = $this->customer_repository->show($customer_id);

            $location = $this->location_repository->getLocationByName();

            $business_website = $this->configuration_repository->findConfigurationColumnsByKey('business_website', 'value')->value;

            $additional_mapping = [
                '{Business Name}' => $location['business_name'],
                '{Email}' => $location['email'],
                '{Phone}' => Utility::format_phone_us($location['phone']),
                '{Address}' => $location['street'] . ' ' . $location['city'] . ' ' . $location['state'] . ' ' . $location['zipcode'],
                '{Website}' => $business_website,
                '{Review Link}' => $review_url,
                '{Short Review Link}' => $short_review_url,
            ];

            try {
                //SEND EMAIL WITH LINK
                if (!empty($customer['email']) && strpos($data['request_review_methods'], "email") !== false) {
                    $account_email_name = $this->configuration_repository->findConfigurationColumnsByKey('account_email_name', ['value']);
                    $account_email_name = $account_email_name->value;

                    $account_email_address = $this->configuration_repository->findConfigurationColumnsByKey('account_email_address', ['value']);
                    $account_email_address = $account_email_address->value;

                    $account_email_template_header = $this->configuration_repository->findConfigurationColumnsByKey('account_email_template_header', ['value']);
                    $account_email_template_header = $account_email_template_header->value;

                    $review_email_subject_template = $this->configuration_repository->findConfigurationColumnsByKey('review_email_subject_template', ['value']);
                    $review_email_subject_template = $review_email_subject_template->value;

                    $review_email_content_template = $this->configuration_repository->findConfigurationColumnsByKey('review_email_content_template', ['value']);
                    $review_email_content_template = $review_email_content_template->value;

                    $content = $this->customer_repository->parseTemplate($customer, $review_email_content_template, $additional_mapping);

                    if (is_file(APPPATH . 'Views/emails/default_customer_review_reply.php')) {
                        $content =  View(
                            'emails/default_customer_review_reply',
                            [
                                'header' => $account_email_template_header,
                                'content' => $content,
                                'review_url' => $review_url,
                                'short_review_url' => $short_review_url
                            ]
                        );
                    }

                    $emailFactory = service('channelService')->getInstance("internal")['email'];
                    $emailFactory->init(
                        getEnv('notification.smtp.host'),
                        getEnv('notification.smtp.username'),
                        getEnv('notification.smtp.password'),
                        getEnv('notification.smtp.port')
                    );

                    $review_email_subject_template = $this->customer_repository->parseTemplate($customer, $review_email_subject_template, $additional_mapping);
                    $emailFactory->setSubject(Utility::stripHTMLTags($review_email_subject_template));
                    $emailFactory->setContent($content);
                    $emailFactory->setSender($account_email_address, $account_email_name);
                    $emailFactory->setRecipient($customer['email']);
                    $emailFactory->setHTML(true);
                    $emailFactory->send();
                }

                //SEND SMS WITH LINK
                if (!empty($customer['mobile']) && strpos($data['request_review_methods'], "sms") !== false) {
                    $review_sms_content_template = $this->configuration_repository->findConfigurationColumnsByKey('review_sms_content_template', ['value']);
                    $review_sms_content_template = $review_sms_content_template->value;
                    $review_sms_content_template = $this->customer_repository->parseTemplate($customer, $review_sms_content_template, $additional_mapping);

                    $smsFactory = service('channelService')->getInstance("internal")['sms'];
                    $smsFactory->setSid(getenv('twilio.sid'));
                    $smsFactory->setAuth_token(getenv('twilio.auth_token'));
                    $smsFactory->setSender(getenv('twilio.phone'));
                    $smsFactory->setContent(Utility::stripHTMLTags($review_sms_content_template));
                    $smsFactory->setRecipient('+1' . $customer['mobile']);
                    $smsFactory->send();
                }

                //Save in Database
                $this->updateReviewFeedBackRecord($customer_id, "SENT");
            } catch (\Exception $ex) {
                //throw $th;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function updates()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();
            $columns = $data;
            if (array_key_exists("review_feedback_ids", $columns)) {
                unset($columns['review_feedback_ids']);
            }

            $columns = array_filter($data, function ($column) {
                return (trim($column)  != '');
            });

            $review_feedback_ids = $data['review_feedback_ids'];
            $data['review_feedback_ids'] = explode(',', $data['review_feedback_ids']);

            if (count($data['review_feedback_ids']) > 0 && count($columns) > 0) {
                $this->repository->updates($data['review_feedback_ids'], $columns);
                (new ReviewFeedbackRepository())->updateSearchIndex($data['review_feedback_ids']);

                $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function render($encrypted_id)
    {
        try {

            $review_urls = [];
            $review_urls[ReviewSource::GOOGLE . '_review_url'] = $this->getReviewUrlForAService(ReviewSource::GOOGLE);
            $review_urls[ReviewSource::FACEBOOK . '_review_url'] = $this->getReviewUrlForAService(ReviewSource::FACEBOOK);

            $decrypted_string = Utility::shortDecryptString($encrypted_id, getenv("password.encryption"));
            $id = str_replace(md5(db_connect()->database) . "-", "", $decrypted_string);
            $customer = $this->customer_repository->show($id);

            $location = (new LocationRepository())->getLocationByName();

            $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
            $business_logo = ($blogo->value == "") ? "" : "/media/logo/" . $blogo->value;

            $this->customerExist($id);

            $this->updateReviewFeedBackRecord($id, "SEEN");

            if (is_file(APPPATH . 'Views/reviews/index.php') && !empty($customer)) {
                $details = $customer;
                $details['business_logo'] = $business_logo;
                $details['location'] = $location;
                $details['encrypted_id'] = $encrypted_id;

                $details = array_merge($details, $review_urls);
                echo view('reviews/index.php', $details);
                exit;
            } else {
                if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                    echo view('errors/html/error_404.php', []);
                    exit;
                }
            }
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Link is not valid."
            ])->send();
        }
    }

    public function updateStatus($status, $encrypted_id)
    {
        try {
            $decrypted_string = Utility::shortDecryptString($encrypted_id, getenv("password.encryption"));
            $id = str_replace(md5(db_connect()->database) . "-", "", $decrypted_string);

            $this->customerExist($id);

            //@TODO : Stop on process completed

            $this->repository->createOrUpdate(['customer_id' => $id, 'response' => ucfirst($status)]);
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Link is not valid."
            ])->send();
        }
    }

    public function updateFeedback($encrypted_id)
    {
        try {
            $data = $this->request->getPost();
            $recaptcha = new \ReCaptcha\ReCaptcha(getenv('google.recaptcha.private'));

            $resp = $recaptcha->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
            if (!$resp->isSuccess()) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                    ['errors' => 'Please validate Recaptcha']
                )->send();
                exit;
            }

            $decrypted_string = Utility::shortDecryptString($encrypted_id, getenv("password.encryption"));
            $id = str_replace(md5(db_connect()->database) . "-", "", $decrypted_string);

            $this->customerExist($id);

            $comment = !empty($data['comment']) ? htmlspecialchars($data['comment']) : '';

            $response = $this->repository->createOrUpdate(['customer_id' => $id, 'comment' => $comment, 'status' => 'COMPLETED']);


            if (isset($response['success'])) {
                $this->sendNotification($response['review_feedback_id']);
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => $ex->getMessage()
            ])->send();
        }
    }

    public function renderToUnregisteredCustomer()
    {
        try {

            $review_urls = [];
            $review_urls[ReviewSource::GOOGLE . '_review_url'] = $this->getReviewUrlForAService(ReviewSource::GOOGLE);
            $review_urls[ReviewSource::FACEBOOK . '_review_url'] = $this->getReviewUrlForAService(ReviewSource::FACEBOOK);

            $location = (new LocationRepository())->getLocationByName();

            $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
            $business_logo = ($blogo->value == "") ? "" : "/media/logo/" . $blogo->value;

            if (is_file(APPPATH . 'Views/reviews/unregistered_customer.php')) {
                $details = [];
                $details['business_logo'] = $business_logo;
                $details['location'] = $location;

                $details = array_merge($details, $review_urls);
                echo view('reviews/unregistered_customer.php', $details);
                exit;
            } else {
                if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                    echo view('errors/html/error_404.php', []);
                    exit;
                }
            }
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => $ex->getMessage()
            ])->send();
        }
    }

    public function createWithOutCustomer()
    {

        $data = $this->request->getPost();

        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('google.recaptcha.private'));

        $resp = $recaptcha->verify($data['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(
                ['errors' => 'Please validate Recaptcha']
            )->send();
            exit;
        }

        $response = $this->customer_repository->create($data);

        if (isset($response['success'])) {
            $comment = empty($data['comment']) ? '' : $data['comment'];

            $feedback_response = $this->repository->createOrUpdate([
                'customer_id' => $response['customer_id'], 'status' => "COMPLETED",
                'sent_counter' => 1, 'comment' => $comment, 'response' => 'Negative'
            ]);

            if (isset($feedback_response['review_feedback_id'])) {
                $this->sendNotification($feedback_response['review_feedback_id']);
            }

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $response])->send();
        }
    }

    private function customerExist($id)
    {
        $customer = $this->customer_repository->show($id);

        if ($customer == null || !isset($customer['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Link is not valid."
            ])->send();
            exit;
        }
    }

    private function updateReviewFeedBackRecord(string $customer_id, string $status)
    {
        $exiting_customer = $this->repository->findByCustomerId($customer_id);
        if (!empty($exiting_customer)) {
            if ($exiting_customer['status'] !== "COMPLETED") {
                $sent_counter = intval($exiting_customer['sent_counter']);
                $sent_counter += 1;
                $this->repository->createOrUpdate(['customer_id' => $customer_id, 'status' => $status, 'sent_counter' => $sent_counter]);
            }
        } else {
            $this->repository->createOrUpdate(['customer_id' => $customer_id, 'status' => $status]);
        }
    }

    private function getReviewUrlForAService(string $service): ?string
    {
        try {
            return ($this->external_review_factory->getInstance($service))->getReviewURI();
        } catch (\Exception $ex) {
            return null;
        }
    }

    private function sendNotification($id)
    {
        try {
            $review_feedback = $this->repository->show($id);
            if (isset($review_feedback)) {

                $customer = $this->customer_repository->show($review_feedback['customer_id']);

                $db = \Config\Database::connect();
                $app_builder = $db->table('app')->get()->getRowArray();
                $app_name = $app_builder['name'];

                $pushFactory = service('channelService')->getInstance("internal")['push'];
                $pushFactory->setSubject("REVIEW:FEEDBACK:RECEIVED");
                $pushFactory->setContent($customer['first_name'] . ' has provided new feedback.');
                $pushFactory->setRecipient($app_name, "*");
                $pushFactory->send();

            }
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
        }
    }
}
