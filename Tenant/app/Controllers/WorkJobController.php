<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\WorkOrderRepository;
use Tenant\app\Repositories\WorkJobCommonDependentRepository;
use Tenant\app\Repositories\WorkJobRepository;

class WorkJobController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the WorkJobRepository which can be used by any method here.
     *
     * @var WorkJobRepository
     */
    private $repository;

    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new WorkJobRepository();
        $this->secondary_repository = new WorkJobCommonDependentRepository();
    }

    public function index($work_order_id)
    {
        $query = [];
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

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

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->exactSearch($search);
            }
        }

        $this->repository->filterData('work_order_id', $work_order_id);

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $this->repository->index(null, $header = null, null, $query, $items, $sort, $paginate, 'work_job_view_id', 'work_job_listing');

        foreach ($data['work_job_listing'] as $key => $value) {
            $data['work_job_listing'][$key]['products'] = $this->secondary_repository->switchWorkJobCommonDependentState('products')->findByWorkJobId($value['id']);
        }

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function show($work_order_id, $id)
    {
        $response = $this->repository->show($id);

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Work Job couldn't be found."])->send();
        }
    }

    public function create($work_order_id)
    {
        $data = $this->request->getPost();
        $data['work_order_id'] = $work_order_id;
        $response = $this->repository->create($data);

        if (isset($response['success'])) {

            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($work_order_id, $id)
    {
        $work_job = $this->repository->show($id);

        if ($work_job == null || !isset($work_job['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Work Job couldn't be found."
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

    public function delete($work_order_id, $id)
    {
        $response = $this->repository->delete($id);
        $this->response->setContentType('application/json')->setJSON(["success" => "Work Job has been deleted."])->send();
    }
}
