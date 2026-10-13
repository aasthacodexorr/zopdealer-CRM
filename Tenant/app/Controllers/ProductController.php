<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ProductRepository;

class ProductController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the ProductRepository which can be used by any method here.
     *
     * @var ProductRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ProductRepository();
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

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'product_view_id', 'product_listing');

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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Product couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $product = $this->repository->show($id);

        if ($product == null || !isset($product['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Product couldn't be found."
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

    public function updates()
    {
        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("product_ids", $columns)) {
            unset($columns['product_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $product_ids = $data['product_ids'];
        $data['product_ids'] = explode(',', $data['product_ids']);

        if (count($data['product_ids']) > 0 && count($columns) > 0) {

            $this->repository->updates($data['product_ids'], $columns);
            (new ProductRepository())->updateSearchIndex($data['product_ids']);

            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (service('authPolicy')->hasElevatedRoles() && !empty($data['product_ids'])) {

            $data['product_ids'] = explode(',', $data['product_ids']);

            if (count($data['product_ids']) > 0) {
                $this->repository->delete($data['product_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
