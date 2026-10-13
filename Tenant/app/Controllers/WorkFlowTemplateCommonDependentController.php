<?php

namespace Tenant\app\Controllers;

use App\Libraries\WorkFlow\WorkFlowConstant;
use Tenant\app\Repositories\WorkFlowTemplateCommonDependentRepository;
use Tenant\app\Repositories\WorkFlowTemplateRepository;

class WorkFlowTemplateCommonDependentController extends \App\Controllers\BaseController
{
    private $repository;

    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new WorkFlowTemplateCommonDependentRepository();
        $this->secondary_repository = new WorkFlowTemplateRepository();
    }

    public function create($workflow_template_id, $model)
    {
        $data = $this->request->getPost();
        $data["workflow_template_id"] = $workflow_template_id;
        $response = $this->repository->switchWorkFlowTemplateCommonDependentState($model)->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($workflow_template_id, $model, $id)
    {

        $data = $this->request->getPost();

        $response = $this->repository->switchWorkFlowTemplateCommonDependentState($model)->update($id, $data);
        if (isset($response['success'])) {
            service('workFlowService')->detach(['template_id' => $workflow_template_id, 'action' => WorkFlowConstant::WORKFLOW_TEMPLATE_DELETE]);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($workflow_template_id, $model, $id)
    {

        $response = $this->repository->switchWorkFlowTemplateCommonDependentState($model)->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this workflow template."])->send();
        }
    }

    public function delete($workflow_template_id, $model, $id)
    {
        try {
            $this->repository->switchWorkFlowTemplateCommonDependentState($model)->delete($id);
            service('workFlowService')->detach(['template_id' => $workflow_template_id, 'action' => WorkFlowConstant::WORKFLOW_TEMPLATE_DELETE]);
            $this->response->setContentType('application/json')->setJSON(["success" => ucfirst($model) . " has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the " . $model . "."])->send();
        }
    }

    public function index($workflow_template_id, $model)
    {

        $response = $this->repository->switchWorkFlowTemplateCommonDependentState($model)->findByWorkFlowTemplateIdWithStepDetails($workflow_template_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ucfirst($model) . " couldn't be found for this workflow template."])->send();
        }
    }

    public function sortIndex($workflow_template_id)
    {
        $data = $this->request->getPost();
        $data["steps"] = json_decode($data["steps"], true);
        $i = 0;
        $rows = [];
        foreach ($data["steps"] as $row) {
            $i += 1;
            array_push($rows, [
                'id' => (int) $row['id'],
                'index' => $i
            ]);

            $this->repository->switchWorkFlowTemplateCommonDependentState('steps')->updateBatch($rows, 'id');
            service('workFlowService')->detach(['template_id' => $workflow_template_id, 'action' => WorkFlowConstant::WORKFLOW_TEMPLATE_DELETE]);
        }

        $this->response->setContentType('application/json')->setJSON(['success' => 'Index Updated.'])->send();
    }
}
