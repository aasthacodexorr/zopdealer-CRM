<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\VehicleDescriptionTemplateRepository;

class VehicleDescriptionTemplateController extends \App\Controllers\BaseController
{
    /**
     *
     * @var VehicleDescriptionTemplateRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new VehicleDescriptionTemplateRepository();
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

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'vehicle_description_template_view_id', 'vehicle_description_template_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Vehicle description template couldn't be found."])->send();
        }
    }

    public function update($id)
    {

        $vehicle_description_template = $this->repository->show($id);

        if ($vehicle_description_template == null || !isset($vehicle_description_template['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Vehicle description template couldn't be found."
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
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => 'Vehicle description template has been deleted.'])->send();
        } catch (\Exception $e) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => 'There were some error in deleting the Vehicle description template.'])->send();
        }
    }
}
