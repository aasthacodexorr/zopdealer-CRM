<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LeadResponseViewRepository;

class LeadResponseViewController extends BaseController
{
    /**
     * repository This repository corresponds to the LeadResponseViewRepository which can be used by any method here.
     *
     * @var LeadResponseViewRepository
     */
    private $repository;
    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new LeadResponseViewRepository();
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["lead_response_views" => $response])->send();
    }

    public function create(): void
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {
        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Lead Response View couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $response = $this->repository->update($data, $id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function result($id)
    {

        if (!$this->isCurrentUserAuthorizedToViewAllLeads()) {
            $this->repository->setEntityAttributeAndFilterValue("assignee", service('authPolicy')->id);
        }

        $response = $this->repository->result($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    private function isCurrentUserAuthorizedToViewAllLeads()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            return true;
        }

        $authorize_staff_to_view_all_leads =  $this->configuration_repository->findConfigurationColumnsByKey('authorize_staff_to_view_all_leads', 'value')->value;
        return !empty($authorize_staff_to_view_all_leads);
    }
}
