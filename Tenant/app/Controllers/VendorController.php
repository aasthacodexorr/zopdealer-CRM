<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\VendorRepository;
use Tenant\app\Repositories\CustomerRepository;

class VendorController extends \App\Controllers\BaseController
{

    private VendorRepository    $repository;

    public function __construct()
    {
        $this->repository = new VendorRepository();
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

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'vendor_view_id', 'vendor_listing');

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

    public function show(string $id)
    {

        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Vendor couldn't be found."])->send();
        }
    }

    public function update(string $id)
    {

        $vendor = $this->repository->show($id);

        if ($vendor == null || !isset($vendor['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Vendor couldn't be found."
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

    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (!empty($data['vendor_ids'])) {

            $data['vendor_ids'] = explode(',', $data['vendor_ids']);

            if (count($data['vendor_ids']) > 0) {
                $this->repository->delete($data['vendor_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "No valid vendors have been selected."])->send();
        }
    }

    public function convertVendor(string $id)
    {

        $vendor = $this->repository->show($id);

        if ($vendor == null || !isset($vendor['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Vendor couldn't be found."
            ])->send();
        } else {

            $customer_repository = new CustomerRepository();

            unset($vendor['id']);
            unset($vendor['created_at']);
            unset($vendor['updated_at']);

            $vendor['customer_type'] = '1';

            $customer = $customer_repository->create($vendor);

            if (isset($customer['success'])) {
                $this->response->setContentType('application/json')->setJSON($customer)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($customer)->send();
            }
        }
    }
}
