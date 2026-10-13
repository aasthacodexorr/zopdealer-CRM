<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\CostSettingRepository;
use App\Policies\AuthPolicy;

class CostSettingController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the CostSettingRepository which can be used by any method here.
     *
     * @var CostSettingRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new CostSettingRepository();
    }

    public function index($type)
    {

        $this->switchModelInRepository($type);

        $items = 5;
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

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->search($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'cost_settings', 'cost_setting_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create($type)
    {
        $this->switchModelInRepository($type);

        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($type, $id)
    {
        $this->switchModelInRepository($type);

        $response = $this->repository->show($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function update($type, $id)
    {
        $data = $this->request->getPost();

        $this->switchModelInRepository($type);

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function delete($type, $id)
    {
        $this->switchModelInRepository($type);

        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => 'Cost Setting has been deleted.'])->send();
        } catch (\Exception $e) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => 'There were some error in deleting the cost setting.'])->send();
        }
    }

    private function switchModelInRepository($type)
    {
        if ($type == 'fees' || $type == 'warranty' || $type == 'guarantee' || $type == 'accessories' || $type == 'insurances') {
            $this->repository->switchCostType($type);
        }

        return $this;
    }
}
