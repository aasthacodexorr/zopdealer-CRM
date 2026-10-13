<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\DealReplicatorCommonDependentRepository;
use Tenant\app\Repositories\DealReplicatorRepository;

class DealReplicatorCommonDependentController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealReplicatorCommonDependentRepository which can be used by any method here.
     *
     * @var DealReplicatorCommonDependentRepository
     */
    private $repository;

    /**
     * secondary_repository This is the second repository which holds reference the the joined model.
     *
     * @var mixed
     */
    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new DealReplicatorCommonDependentRepository();
        $this->secondary_repository = new DealReplicatorRepository();
    }

    public function create($deal_replicator_id, $model)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();
            $data["deal_replicator_id"] = $deal_replicator_id;
            $response = $this->repository->switchDealReplicatorCommonDependentState($model)->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function update($deal_replicator_id, $model, $id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $response = $this->repository->switchDealReplicatorCommonDependentState($model)->update($id, $data);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function show($deal_replicator_id, $model, $id)
    {

        $response = $this->repository->switchDealReplicatorCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this deal."])->send();
        }
    }

    public function delete($deal_replicator_id, $model, $id)
    {

        if (service('authPolicy')->hasElevatedRoles()) {

            try {
                $this->repository->switchDealReplicatorCommonDependentState($model)->delete($id);
                $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
            } catch (\Exception $exception) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function index($deal_replicator_id, $model)
    {

        $response = $this->repository->switchDealReplicatorCommonDependentState($model)->findByDealReplicatorId($deal_replicator_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this deal."])->send();
        }
    }
}
