<?php

namespace Tenant\app\Controllers;

use App\Libraries\WorkFlow\WorkFlowConstant;
use Tenant\app\Repositories\WorkFlowTemplateRepository;

class WorkFlowTemplateController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the WorkFlowTemplateRepository which can be used by any method here.
     *
     * @var WorkFlowTemplateRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new WorkFlowTemplateRepository();
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

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'workflow_template_view_id', 'workflow_template_listing');

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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Workflow Template couldn't be found."])->send();
        }
    }

    public function delete($id)
    {

        $this->repository->delete($id);
        service('workFlowService')->detach(['template_id' => $id, 'action' => WorkFlowConstant::WORKFLOW_TEMPLATE_DELETE]);
        $this->response->setContentType('application/json')->setJSON(["success" => "WorkFlow Template has been deleted."])->send();
    }
}
