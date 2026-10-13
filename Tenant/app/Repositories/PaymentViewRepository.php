<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Entities\VendorView;
use Tenant\app\Models\PaymentViewModel;
use Tenant\app\Repositories\VendorRepository;

/**
 * PaymentViewRepository CRUD and complex queries for PaymentView Model
 */
class PaymentViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new PaymentViewModel());
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
     * creates new Payment View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $vendor_view_id = $this->model->insertID;
            return [
                "success" => 'New Payment View Created',
                "id" => $vendor_view_id
            ];
        }
    }
    
    /**
     * update Updates the Payment View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id) : array
    {

        $paymentView = new Entity($data);
        $paymentView->id = $id;
        
        if ($this->model->save($paymentView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Payment View Response has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $paymentRepository = new PaymentRepository();
        
        $iv =  $this->model->find($id);
        return $paymentRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort,true,'payment_view_id','payment_listing' );

    }

    
}