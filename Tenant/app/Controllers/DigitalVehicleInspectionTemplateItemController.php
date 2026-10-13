<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\DigitalVehicleInspectionTemplateItemRepository;

class DigitalVehicleInspectionTemplateItemController extends \App\Controllers\BaseController
{
    /**
     *
     * @var DigitalVehicleInspectionTemplateItemRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new DigitalVehicleInspectionTemplateItemRepository();
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

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'digital_vehicle_inspection_template_item_view_id', 'digital_vehicle_inspection_template_item_listing');

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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI Template Item couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $digital_vehicle_inspection_template_item = $this->repository->show($id);

        if ($digital_vehicle_inspection_template_item == null || !isset($digital_vehicle_inspection_template_item['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "DVI Template Item couldn't be found."
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

        $this->repository->delete($id);
        $this->response->setContentType('application/json')->setJSON(["success" => "DVI Template Item has been deleted."])->send();
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();

        $data['digital_vehicle_inspection_template_item_ids'] = explode(',', $data['digital_vehicle_inspection_template_item_ids']);

        if (count($data['digital_vehicle_inspection_template_item_ids']) > 0) {
            $this->repository->multipleDeletes($data['digital_vehicle_inspection_template_item_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }
}
