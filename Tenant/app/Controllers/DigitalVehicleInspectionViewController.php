<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\DigitalVehicleInspectionViewRepository;

class DigitalVehicleInspectionViewController extends BaseController
{
    /**
     * repository This repository corresponds to the DigitalVehicleInspectionViewRepository which can be used by any method here.
     *
     * @var DigitalVehicleInspectionViewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new DigitalVehicleInspectionViewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["digital_vehicle_inspection_views" => $response])->send();
    }

    public function show($id)
    {
        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI View couldn't be found."])->send();
        }
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
