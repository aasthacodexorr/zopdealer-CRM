<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Entities\LeadResponseView;
use Tenant\app\Models\LeadResponseViewModel;
use Tenant\app\Repositories\CraigslistViewRepository;
use Tenant\app\Repositories\FacebookMarketPlaceViewRepository;
use Tenant\app\Repositories\VendorViewRepository;

class CraigslistControllerViewController extends BaseController
{
    /**
     * repository This repository corresponds to the CraigslistViewRepository which can be used by any method here.
     *
     * @var CraigslistViewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new CraigslistViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["craigslist_views" => $response])->send();
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
        $response = $this->repository->result($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
}
