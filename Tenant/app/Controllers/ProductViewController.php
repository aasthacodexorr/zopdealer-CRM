<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\PaymentViewRepository;
use Tenant\app\Repositories\ProductViewRepository;

class ProductViewController extends BaseController
{
    /**
     * repository This repository corresponds to the ProductViewRepository which can be used by any method here.
     *
     * @var ProductViewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ProductViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["product_views" => $response])->send();
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
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = $this->repository->result($id);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
