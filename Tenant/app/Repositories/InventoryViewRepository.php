<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Models\InventoryViewModel;
use Tenant\app\Entities\InventoryView;
use Tenant\app\Repositories\InventoryRepository;

/**
 * InventoryViewRepository CRUD and complex queries for InventoryView Model
 */
class InventoryViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new InventoryViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * creates new Inventory View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new InventoryView($data)) === false) {
            return $this->model->errors();
        } else {
            $inventory_view_id = $this->model->insertID;
            return [
                "success" => 'New Inventory View Created',
                "id" => $inventory_view_id
            ];
        }
    }

    /**
     * update Updates the Inventory View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $inventoryView = new InventoryView($data);
        $inventoryView->id = $id;

        if ($this->model->save($inventoryView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Inventory View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $inventoryRepository = new InventoryRepository();

        $iv =  $this->model->find($id);
        return $inventoryRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'inventory_id', 'inventory');
    }
}

