<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use Tenant\app\Repositories\ActivityViewRepository;

class ActivityViewController extends BaseController
{
    /**
     * repository This repository corresponds to the ActivityRepository which can be used by any method here.
     *
     * @var ActivityViewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ActivityViewRepository();
    }


    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["activities_views" => $response])->send();
    }

    public function create(): void
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $response = $this->repository->update($data, $id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function result($id)
    {
        $response = null;

        $items = 100;

        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        $paginate = false;

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate = $paginate == "true" ? true : false;
        }
        
        if ($this->request->getGet('zm_date_start') && $this->request->getGet('zm_date_end')) {
            $calendar_start_date = $this->request->getGet('zm_date_start');
            $calendar_end_date = $this->request->getGet('zm_date_end');
            $this->repository->setEntityQuery(
                'start_date',
                "AND",
                "between",
                "Date Comparision",
                array_shift($calendar_start_date),
                array_shift($calendar_end_date)
            );
        }

        if (!service('authPolicy')->hasElevatedRoles()) {
            $response = $this->repository->result($id, $items, $paginate, service('authPolicy')->id);
        } else {
            $response = $this->repository->result($id, $items, $paginate);
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
}
