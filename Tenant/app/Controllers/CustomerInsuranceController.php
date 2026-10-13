<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\CustomerInsuranceRepository;
use App\Policies\AuthPolicy;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerRepository;

class CustomerInsuranceController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the CustomerInsuranceRepository which can be used by any method here.
     *
     * @var CustomerInsuranceRepository
     */
    private $repository;

    /**
     * secondary_repository
     *
     * @var CustomerRepository
     */
    private $secondary_repository;

    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new CustomerInsuranceRepository();
        $this->secondary_repository = new CustomerRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function create($customer_id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $data = $this->request->getPost();
        $data["customer_id"] = $customer_id;
        $response = $this->repository->create($data);


        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($customer_id, $id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $response = $this->repository->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Customer insurance couldn't be found for this customer."])->send();
        }
    }

    public function update($customer_id, $id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function delete($customer_id, $id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Customer insurance has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting customer insurance."])->send();
        }
    }

    public function index($customer_id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $response = $this->repository->findByCustomerId($customer_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Customer insurance couldn't be found for this customer."])->send();
        }
    }

    /**
     * existenceOrRightsCheckForCustomer This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForCustomer($customer)
    {
        if ($customer == null || !isset($customer['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Customer couldn't be found."
            ])->send();
            exit;
        }

        if (!$this->isCurrentUserAuthorizedToViewCurrentCustomer($customer)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privelages to see the customer."])->send();
            exit;
        }
    }

    private function isCurrentUserAuthorizedToViewCurrentCustomer($customer)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_customers =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_customers', 'value')->value;
        $authorize_staff_to_view_all_customers =  !empty($authorize_staff_to_view_all_customers);

        if ($authorize_staff_to_view_all_customers) {
            return true;
        }

        return $customer['assignee'] == service('authPolicy')->id;
    }
}
