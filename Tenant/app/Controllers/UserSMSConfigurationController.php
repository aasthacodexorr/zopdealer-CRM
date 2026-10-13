<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\UserSMSConfigurationRepository;

class UserSMSConfigurationController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the User's SMS Configuration
     *
     * @var UserSMSConfigurationRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new UserSMSConfigurationRepository();
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
                $data = $this->repository->search($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'User SMS Configuration_view_id', 'User SMS Configuration_listing');
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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "User SMS Configuration couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $user_sms_configuration = $this->repository->show($id);

        if ($user_sms_configuration == null || !isset($user_sms_configuration['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "User SMS Configuration couldn't be found."
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
}
