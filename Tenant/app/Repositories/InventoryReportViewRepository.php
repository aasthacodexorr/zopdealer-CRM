<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Entities\DealView;
use Tenant\app\Models\InventoryReportModel;
use Tenant\app\Models\InventoryReportViewModel;
use Tenant\app\Repositories\DealRepository;

/**
 * InventoryReportViewRepository CRUD and complex queries for DealReportView Model
 */
class InventoryReportViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new InventoryReportViewModel());
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
     * creates new Deal Report View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new DealView($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $inventory_view_id = $this->model->insertID;
            return [
                "success" => 'Inventory Report View Created',
                "id" => $inventory_view_id
            ];
        }
    }
    
    /**
     * update Updates the Deal Report View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id){

        $dealView = new DealView($data);
        $dealView->id = $id;
        
        if ($this->model->save($dealView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Inventory Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $dealRepository = new DealRepository();
        $dealRepository->setModel(new InventoryReportModel());
        
        $iv =  $this->model->find($id);
        return $dealRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort, $pagination,'inventory_view_id','inventory_listing' );

    }

    
}