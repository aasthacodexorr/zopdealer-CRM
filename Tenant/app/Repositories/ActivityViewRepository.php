<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Traits\EntityBasedQueryTrait;
use Tenant\app\Entities\DealView;
use Tenant\app\Models\ActivityViewModel;

/**
 * ActivityViewRepository CRUD and complex queries for ActivityView Model
 */
class ActivityViewRepository extends BaseRepository
{
    use EntityBasedQueryTrait;

    public function __construct()
    {
        parent::__construct(new ActivityViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    /**
     * creates new Activity View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new DealView($data)) === false) {
            return $this->model->errors();
        } else {
            $activity_view_id = $this->model->insertID;
            return [
                "success" => 'Activity View Created',
                "id" => $activity_view_id
            ];
        }
    }

    /**
     * update Updates the Activity View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $activityView = new DealView($data);
        $activityView->id = $id;

        if ($this->model->save($activityView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Activity View has been updated.',
            ];
        }
    }

    public function result($id, $items = 100, $paginate = false, $user_id = null)
    {
        $activityRepository = new ActivityRepository();

        if (isset($user_id)) {
            $activityRepository->filterData("assignee", $user_id);
            $activityRepository->orPublicActivity();
        }

        $iv =  $this->model->find($id);
        return $activityRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $this->getEntityQuery(), $items, $iv->sort, $paginate, 'activity_view_id', 'activity_listing');
    }
}
