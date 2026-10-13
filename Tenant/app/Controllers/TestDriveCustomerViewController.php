<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\TestDriveCustomerViewRepository;

class TestDriveCustomerViewController extends BaseController
{
    /**
     * repository This repository corresponds to the TestDrivesCustomerViewRepository which can be used by any method here.
     *
     * @var TestDriveCustomerViewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new TestDriveCustomerViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["test_drives_customers_view" => $response])->send();
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
