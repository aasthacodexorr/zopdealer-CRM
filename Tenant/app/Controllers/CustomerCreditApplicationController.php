<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\CustomerCreditApplicationRepository;
use App\Policies\AuthPolicy;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerRepository;

class CustomerCreditApplicationController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the CustomerCreditApplicationRepository which can be used by any method here.
     *
     * @var CustomerCreditApplicationRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new CustomerCreditApplicationRepository();
        $this->secondary_repository = new CustomerRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function create($customer_id)
    {

        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $customer_type = (int) $customer["customer_type"];
        $switch_customer_type = $customer_type == 0 ? 1 : 0;

        $existing_entity = $this->repository->switchCustomerState($switch_customer_type)->findByCustomerId($customer["id"]);

        $data = $this->request->getPost();
        $data["customer_id"] = $customer_id;
        $response = $this->repository->switchCustomerState($customer_type)->create($data);

        if (isset($response['success'])) {
            //clean old entity if new creation is successful.

            if ($existing_entity != null) {
                $this->repository->switchCustomerState($switch_customer_type)->filterByCustomerId($customer["id"])->deleteWithoutKey();
            }
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($customer_id, $id)
    {

        //find customer details
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        //use customer_type to switch the customer credit application
        $customer_type = (int) $customer["customer_type"];

        //match the credit application id with the id above. 
        $current_entity = $this->repository->switchCustomerState($customer_type)->findByCustomerId($customer_id);

        //mismatch throw error
        if ((int) $current_entity["id"] != (int) $id) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Credit Application couldn't be found for this Customer."])->send();
            exit;
        }

        $response = $this->repository->switchCustomerState($customer_type)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Inventory purchase couldn't be found for this inventory."])->send();
        }
    }

    public function update($customer_id, $id)
    {

        //find customer details
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);


        //use customer_type to switch the customer credit application
        $customer_type = (int) $customer["customer_type"];

        //match the credit application id with the id above. 
        $current_entity = $this->repository->switchCustomerState($customer_type)->findByCustomerId($customer_id);

        //mismatch throw error
        if ((int) $current_entity["id"] != (int) $id) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Credit Application couldn't be found for this Customer."])->send();
            exit;
        }

        $data = $this->request->getPost();

        $response = $this->repository->switchCustomerState($customer_type)->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function delete($customer_id, $id)
    {
        //find customer details
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        //use customer_type to switch the customer credit application
        $customer_type = (int) $customer["customer_type"];

        //match the credit application id with the id above. 
        $current_entity = $this->repository->switchCustomerState($customer_type)->findByCustomerId($customer_id);

        //mismatch throw error
        if ((int) $current_entity["id"] != (int) $id) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Credit Application couldn't be found for this Customer."])->send();
            exit;
        }

        try {
            $this->repository->switchCustomerState($customer_type)->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Inventory Purchase has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting inventory purchase."])->send();
        }
    }

    public function index($customer_id)
    {
        $customer = $this->secondary_repository->show($customer_id);
        $this->existenceOrRightsCheckForCustomer($customer);

        $customer_type = (int) $customer["customer_type"];

        $response = $this->repository->switchCustomerState($customer_type)->findByCustomerId($customer_id);

        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Credit Application couldn't be found for this Customer."])->send();
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
