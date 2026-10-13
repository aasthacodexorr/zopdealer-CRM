<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\InventoryCostReportModel;
use Tenant\app\Models\InventoryCostReportViewModel;

/**
 * InventoryCostReportViewRepository CRUD and complex queries for InventoryCostReportView Model
 */
class InventoryCostReportViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new InventoryCostReportViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }


    /**
     * show This shows single instance of entity
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * creates new Payment Report View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors();
        } else {
            $inventory_report_view_id = $this->model->insertID;
            return [
                "success" => 'Inventory Cost Report View Created',
                "id" => $inventory_report_view_id
            ];
        }
    }

    /**
     * update Updates the Inventory Cost Report View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $inventoryReportView = new Entity($data);
        $inventoryReportView->id = $id;

        if ($this->model->save($inventoryReportView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Inventory Cost Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $inventoryCostRepository = new InventoryCostRepository();
        $inventoryCostRepository->setModel(new InventoryCostReportModel());

        $iv =  $this->model->find($id);
        return $inventoryCostRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, $pagination, 'inventory_cost_view_id', 'inventory_cost_listing');
    }
}
