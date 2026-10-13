<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Traits\EntityBasedFilterTrait;
use Tenant\app\Models\CustomerViewModel;
use Tenant\app\Entities\CustomerView;
use Tenant\app\Repositories\CustomerRepository;

/**
 * CustomerViewRepository CRUD and complex queries for CustomerView Model
 */
class CustomerViewRepository extends BaseRepository
{
    use EntityBasedFilterTrait;

    public function __construct(){
        parent::__construct(new CustomerViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
       
    }

    /**
     * creates new Customer View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new CustomerView($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $customer_view_id = $this->model->insertID;
            return [
                "success" => 'New Customer View Created',
                "id" => $customer_view_id
            ];
        }
    }
    
    /**
     * update Updates the Customer View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id) : array
    {

        $customerView = new CustomerView($data);
        $customerView->id = $id;
        
        if ($this->model->save($customerView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Customer View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $customerRepository = new CustomerRepository();
        $this->applyEntityFilters($customerRepository);
        
        $iv =  $this->model->find($id);
        return $customerRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort,true,'customer_view_id','customer_listing' );

    }

    
}