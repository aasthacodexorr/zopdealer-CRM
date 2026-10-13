<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\WorkOrderViewModel;

/**
 * WorkOrderViewRepository CRUD and complex queries for WorkOrderView Model
 */
class WorkOrderViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new WorkOrderViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    /**
     * creates new Work Order View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors();
        } else {
            $work_order_view_id = $this->model->insertID;
            return [
                "success" => 'New Work Order View Created',
                "id" => $work_order_view_id
            ];
        }
    }

    /**
     * update Updates the Work Order View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $workOrderView = new Entity($data);
        $workOrderView->id = $id;

        if ($this->model->save($workOrderView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Work Order View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $workOrderRepository = new WorkOrderRepository();

        $iv =  $this->model->find($id);
        return $workOrderRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'work_order_view_id', 'work_order_listing');
    }
}
