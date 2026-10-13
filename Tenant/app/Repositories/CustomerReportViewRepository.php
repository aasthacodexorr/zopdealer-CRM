<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\CustomerReportViewModel;

/**
 * CustomerReportViewRepository CRUD and complex queries for CustomerReportViewModel Model
 */
class CustomerReportViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new CustomerReportViewModel());
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
     * creates new Customer Report View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $deal_view_id = $this->model->insertID;
            return [
                "success" => 'Customer Report View Created',
                "id" => $deal_view_id
            ];
        }
    }
    
    /**
     * update Updates the Customer Report View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id){

        $paymentView = new Entity($data);
        $paymentView->id = $id;
        
        if ($this->model->save($paymentView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Customer Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $customerRepository = new CustomerRepository();

        $iv =  $this->model->find($id);
        return $customerRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, $pagination, 'customer_view_id', 'customer_listing');
    }

    
}