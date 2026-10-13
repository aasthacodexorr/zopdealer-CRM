<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Entities\DealView;
use Tenant\app\Models\DealReportModel;
use Tenant\app\Models\DealReportViewModel;
use Tenant\app\Repositories\DealRepository;

/**
 * DealReportViewRepository CRUD and complex queries for DealReportView Model
 */
class DealReportViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new DealReportViewModel());
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
            $deal_view_id = $this->model->insertID;
            return [
                "success" => 'Deal Report View Created',
                "id" => $deal_view_id
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
                "success" => 'Deal Report View has been updated.',
            ];
        }
    }

    public function result($id, $user_id = null, $pagination = true)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $dealRepository = new DealRepository();
        $dealRepository->setModel(new DealReportModel());

        if(isset($user_id)){
            $dealRepository->filterData("salesperson", $user_id);
        }
        
        $iv =  $this->model->find($id);
        return $dealRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort, $pagination,'deal_view_id','deal_listing' );

    }

    
}