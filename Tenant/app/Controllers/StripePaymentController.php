<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\StripePayment\PaymentContextState;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\StripePaymentRepository;

class StripePaymentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the StripePaymentRepository which can be used by any method here.
     *
     * @var StripePaymentRepository
     */
    private $repository;

    /**
     * stripe_payment_context
     *
     * @var PaymentContextState
     */
    private $stripe_payment_context;

    public function __construct()
    {
        $this->repository = new StripePaymentRepository();
        $this->stripe_payment_context = new PaymentContextState();
    }

    public function create()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'name'  => [
                "label" => 'Name',
                "rules" => 'required'
            ],
            'email'  => [
                "label" => 'Email',
                "rules" => 'required|valid_email'
            ],
            'phone'  => [
                "label" => 'Phone Number',
                "rules" => 'permit_empty|is_natural|exact_length[10]'
            ],
            'amount'  => [
                "label" => 'Amount',
                "rules" => 'required|decimal'
            ],
            "module" => [
                "label" => 'Module',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ],
            "module_id" => [
                "label" => 'Module Id',
                "rules" => 'permit_empty|is_natural'
            ]
        ]);

        $module = empty($data['module']) ? "Generic" : $data['module'];
        $module_id = empty($data['module_id']) ? "" : $data['module_id'];

        if ($validated) {
            $response["payment_url"] = $this->stripe_payment_context->encrypt((float) $data['amount'], $module, $module_id, false, $data);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function showPaymentPage(string $payload)
    {

        $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');
        $business_logo = ($blogo->value == "") ? "" : "/media/logo/" . $blogo->value;

        $response = $this->stripe_payment_context->decrypt($payload);

        if (is_file(APPPATH . 'Views/payments/index.php') && !empty($response['amount'])) {
            $response['business_logo'] = $business_logo;
            echo view('payments/index.php', $response);
            exit;
        } else {
            if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                echo view('errors/html/error_404.php', []);
                exit;
            }
        }
    }

    public function capturePayment()
    {
        $data = $this->stripe_payment_context->capture();

        if (empty($data['database'])) {
            exit;
        }

        $db = \Config\Database::connect();
        $db->setDatabase($data['database']);

        $capture_payment_types = ['Refund', 'Charge'];

        if (empty($data['errors']) && in_array($data['status'], $capture_payment_types)) {
            unset($data['date']);
            $this->repository->create($data);
            $amount = empty($data['amount']) ? 0 : (float) $data['amount'] / 100;
            $customer = empty($data['name']) ? '' : $data['name'];
            $action = $data['status'] === "Charge" ? " received from " : " refunded to ";
            if (!empty($amount)) {
                $this->notifyDealership("Payment of CA$" . $amount . ' has been ' . $action . $customer);
            }

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($data)->send();
        }
    }

    public function refundPayment()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $validated = $this->validate([
                'transaction_id'  => [
                    "label" => 'Name',
                    "rules" => 'required'
                ],
                'amount'  => [
                    "label" => 'Amount',
                    "rules" => 'required|decimal'
                ]
            ]);

            if ($validated) {

                $response = $this->stripe_payment_context->refund($data['transaction_id'], (float) $data['amount']);

                if (empty($response) || isset($response['errors'])) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
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
        $pushFactory->setSubject("PAYMENT:RECEIVED");
        $pushFactory->setContent($content);

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }
}
