<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\InventoryPurchaseReportModel;
use Tenant\app\Models\InventoryPurchaseReportViewModel;

/**
 * InventoryPurchaseReportViewRepository CRUD and complex queries for InventoryPurchaseReportView Model
 */
class InventoryPurchaseReportViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new InventoryPurchaseReportViewModel());
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
            $inventory_purchase_report_view_id = $this->model->insertID;
            return [
                "success" => 'Inventory Purchase Report View Created',
                "id" => $inventory_purchase_report_view_id
            ];
        }
    }

    /**
     * update Updates the Inventory Purchase Report View.
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
                "success" => 'Inventory Purchase Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $inventoryPurchaseRepository = new InventoryPurchaseRepository();
        $inventoryPurchaseRepository->setModel(new InventoryPurchaseReportModel());

        $iv =  $this->model->find($id);
        return $inventoryPurchaseRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, $pagination, 'inventory_purchase_view_id', 'inventory_purchase_listing');
    }
}
