<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\CostVariationMatrixRepository;

class CostVariationMatrixController extends \App\Controllers\BaseController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new CostVariationMatrixRepository();
    }

    public function index()
    {

        $items = 100;
        $paginate = true;


        $column = 'created_at';
        $order = 'DESC';
        $query = null;

        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate = $paginate == 'false' ? false : true;
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

        $sort = '{"' . $column . '" : "' . $order . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'cost_variation_matrix_view_id', 'cost_variation_matrix_listing');

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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Cost Variation Matrix couldn't be found."])->send();
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Cost variation has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting cost variation."])->send();
        }
    }
}
