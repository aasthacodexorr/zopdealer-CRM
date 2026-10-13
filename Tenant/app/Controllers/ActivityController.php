<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ActivityRepository;

class ActivityController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the ActivityRepository which can be used by any method here.
     *
     * @var ActivityRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ActivityRepository();
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

        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }


        $data = $this->repository;

        $lead_specific_activities = $this->request->getGet('zm_module') && $this->request->getGet('zm_module_id');


        if (!service('authPolicy')->hasElevatedRoles() && !$lead_specific_activities) {
            $data->filterData("assignee", service('authPolicy')->id);
        }

        if ($this->request->getGet('only_today')) {
            if ($this->request->getGet('only_today') == "true") {
                $data->filterOnToday("start_date");
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'activity_view_id', 'activity_listing');

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

        if (!empty($response)) {
            $this->activityAuthorization($response);
        }

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Activity couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $activity = $this->repository->show($id);

        if (!empty($activity)) {
            $this->activityAuthorization($activity);
        }

        if ($activity == null || !isset($activity['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Activity couldn't be found."
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
        $activity = $this->repository->show($id);

        if (!empty($activity)) {
            if (!(service('authPolicy')->hasOwnerRole()
                || service('authPolicy')->isSuperOwner()
                || service('authPolicy')->isResourceOwner($activity['assignee']))) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
                exit;
            }
        }

        if ($activity == null || !isset($activity['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Activity couldn't be found."
            ])->send();
        } else {

            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Activity has been deleted."])->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();

        if (!empty($data['activity_ids'])) {

            $data['activity_ids'] = explode(',', $data['activity_ids']);

            if (count($data['activity_ids']) > 0) {
                $this->repository->delete($data['activity_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'There were some errors in deleting data.'])->send();
        }
    }

    /**
     * updates This method accepts multiple inventory_ids to perform bulk updates for the inventory.
     *
     * @return void
     */
    public function updates()
    {
        $data = $this->request->getPost();

        $columns = $data;

        if (array_key_exists("activity_ids", $columns)) {
            unset($columns['activity_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $data['activity_ids'] = explode(',', $data['activity_ids']);

        if (count($data['activity_ids']) > 0 && count($columns) > 0) {

            $this->repository->updates($data['activity_ids'], $columns);

            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    private function activityAuthorization(array $activity)
    {

        $is_activity_owner_or_super_user = service('authPolicy')->hasOwnerRole() ||
            service('authPolicy')->isSuperOwner() || service('authPolicy')->isResourceOwner($activity['assignee']);

        $is_public = intval($activity['is_private']) === 0;

        if ($is_activity_owner_or_super_user || $is_public) {
            return;
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            exit;
        }
    }
}
