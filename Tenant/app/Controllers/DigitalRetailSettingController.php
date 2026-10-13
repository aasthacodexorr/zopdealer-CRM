<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\ConfigurationRepository;

class DigitalRetailSettingController extends BaseController
{

    private $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function showFinanceTerms(): void
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = $this->configuration_repository->findConfigurationColumnsByKey('digital_retailing_default_finance_terms');
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function updateFinanceTerms(): void
    {
        if (service('authPolicy')->hasElevatedRoles()) {

            $has_errors = false;
            $errors = [];

            $data = $this->request->getPost();

            

            foreach ($data as $key => $row) {

                $validation = \Config\Services::validation(null, false);
                $validation->setRules(
                    [
                        'term'  => [
                            "label" => 'Term',
                            "rules" => 'required|is_natural|less_than_equal_to[120]'
                        ],
                        'downpayment'  => [
                            "label" => 'Downpayment',
                            "rules" => 'required|is_natural'
                        ],
                        'interest_rate'  => [
                            "label" => 'Interest Rate',
                            "rules" => 'required|decimal|less_than_equal_to[100]'
                        ]
                    ]
                );

                if (!$validation->run($row)) {
                    $has_errors = true;
                    $errors[$key] = $validation->getErrors();
                } else {
                    $errors[$key] = [];
                }
            }

            if (!$has_errors) {
                $json_object = json_encode($data);
                $this->configuration_repository->setConfiguration(['digital_retailing_default_finance_terms' => $json_object]);
                $this->response->setContentType('application/json')->setJSON(['success' => 'Financial Terms have been saved.'])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
