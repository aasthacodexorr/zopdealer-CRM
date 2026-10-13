<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\DigitalVehicleInspectionTemplateRepository;

class DigitalVehicleInspectionTemplateController extends \App\Controllers\BaseController
{
    /**
     *
     * @var DigitalVehicleInspectionTemplateRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new DigitalVehicleInspectionTemplateRepository();
    }

    public function index()
    {

        $items = 100;
        $paginate = true;


        $column = 'created_at';
        $order = 'DESC';


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->repository;

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'digital_vehicle_inspection_template_view_id', 'digital_vehicle_inspection_template_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();
            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function show($id)
    {
        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI Template couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $digital_vehicle_inspection_template = $this->repository->show($id);

            if ($digital_vehicle_inspection_template == null || !isset($digital_vehicle_inspection_template['id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                    "errors" => "DVI Template couldn't be found."
                ])->send();
            } else {
                $data = $this->request->getPost();

                $response = $this->repository->update($id, $data);
                if (isset($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function delete($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "DVI Template has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
