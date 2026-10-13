<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\CustomerViewRepository;

class CustomerViewController extends BaseController
{
    /**
     * repository This repository corresponds to the CustomerViewRepository which can be used by any method here.
     *
     * @var CustomerViewRepository
     */
    private $repository;

    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new CustomerViewRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }
    
    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["customer_views" => $response])->send();
    }

    public function create() : void
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);
       if(isset($response['success'])){
            $this->response->setContentType('application/json')->setJSON($response)->send();
       }else{
           $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
       }
    }
    
    public function update($id){
        $data = $this->request->getPost();
        $response = $this->repository->update($data,$id);
       if(isset($response['success'])){
            $this->response->setContentType('application/json')->setJSON($response)->send();
       }else{
           $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
       }
    }

    public function result($id){

        if (!$this->isCurrentUserAuthorizedToViewAllCustomers()) {
            $this->repository->setEntityAttributeAndFilterValue("assignee", service('authPolicy')->id);
        }

        $response = $this->repository->result($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();

    }

    private function isCurrentUserAuthorizedToViewAllCustomers()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_customers =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_customers', 'value')->value;
        return !empty($authorize_staff_to_view_all_customers);
    }
}