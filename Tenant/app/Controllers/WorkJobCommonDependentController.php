<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\WorkJobCommonDependentRepository;
use Tenant\app\Repositories\WorkJobRepository;

class WorkJobCommonDependentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the WorkJobCommonDependentRepository which can be used by any method here.
     *
     * @var WorkJobCommonDependentRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var WorkJobRepository
     */
    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new WorkJobCommonDependentRepository();
        $this->secondary_repository = new WorkJobRepository();
    }

    public function create($work_job_id, $model)
    {
        $data = $this->request->getPost();
        $data["work_job_id"] = $work_job_id;
        $response = $this->repository->switchWorkJobCommonDependentState($model)->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($work_job_id, $model, $id)
    {
        $data = $this->request->getPost();

        $response = $this->repository->switchWorkJobCommonDependentState($model)->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($work_job_id, $model, $id)
    {
        $response = $this->repository->switchWorkJobCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this work job."])->send();
        }
    }

    public function delete($work_job_id, $model, $id)
    {

        try {
            $this->repository->switchWorkJobCommonDependentState($model)->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function index($work_job_id, $model)
    {

        $response = $this->repository->switchWorkJobCommonDependentState($model)->findByWorkJobId($work_job_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this work job."])->send();
        }
    }
}
