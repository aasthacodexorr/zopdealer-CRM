<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\VoucherTemplateRepository;

class VoucherTemplateController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the VoucherTemplateRepository which can be used by any method here.
     *
     * @var VoucherTemplateRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new VoucherTemplateRepository();
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

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'voucher_template_view_id', 'voucher_template_listing');

        if (count($data['voucher_template_listing']) > 0) {
            foreach ($data['voucher_template_listing'] as $key => $value) {
                $voucher_template = $data['voucher_template_listing'][0];
                $data['voucher_template_listing'][0]['calculated_expiry_date'] =
                    $voucher_template['expiry_date_type'] === 'STATIC' ? $voucher_template['expiry_date'] :
                    $this->repository->calculateRelativeDateFromToday($voucher_template['expiry_date_interval'], $voucher_template['expiry_date_interval_unit']);
            }
        }

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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Voucher Template couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $voucher_template = $this->repository->show($id);

        if ($voucher_template == null || !isset($voucher_template['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Voucher Template couldn't be found."
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
        $this->response->setContentType('application/json')->setJSON(["success" => "Voucher Template has been deleted."])->send();
    }
}
