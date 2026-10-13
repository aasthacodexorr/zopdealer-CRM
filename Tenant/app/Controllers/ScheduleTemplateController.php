<?php

namespace Tenant\app\Controllers;

use App\Libraries\Schedule\ScheduleConstant;
use Tenant\app\Repositories\ScheduleTemplateRepository;

class ScheduleTemplateController extends \App\Controllers\BaseController
{
    private ScheduleTemplateRepository  $repository;

    public function __construct()
    {
        $this->repository = new ScheduleTemplateRepository();
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

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'schedule_template_view_id', 'schedule_template_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {

        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $enabled = intval($data['enable']);
            $status = $enabled > 0 ? ScheduleConstant::ACTIVE : ScheduleConstant::DISABLED;
            $this->prepareDataAndAttachScheduleToQueue($response['schedule_template_id'], $status);
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
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Schedule Template couldn't be found."])->send();
        }
    }

    public function update($id)
    {

        $schedule = $this->repository->show($id);

        if ($schedule == null || !isset($schedule['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Schedule Template couldn't be found."
            ])->send();
        } else {
            $data = $this->request->getPost();

            $response = $this->repository->update($id, $data);
            if (isset($response['success'])) {
                $enabled = intval($data['enable']);
                $status = $enabled > 0 ? ScheduleConstant::ACTIVE : ScheduleConstant::DISABLED;
                $this->prepareDataAndAttachScheduleToQueue($response['schedule_template_id'], $status);
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        }
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        $this->response->setContentType('application/json')->setJSON(["success" => "Schedule Template has been deleted."])->send();
    }

    private function prepareDataAndAttachScheduleToQueue($entity_id, $status)
    {
        exec("php index.php ScheduleQueue accept  " . db_connect()->database . "   " . $entity_id . "    " . $status);
    }
}
