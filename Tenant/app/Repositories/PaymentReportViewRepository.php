<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\PaymentReportViewModel;

/**
 * PaymentReportViewRepository CRUD and complex queries for PaymentReportView Model
 */
class PaymentReportViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new PaymentReportViewModel());
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
    public function create($data) : array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $deal_view_id = $this->model->insertID;
            return [
                "success" => 'Payment Report View Created',
                "id" => $deal_view_id
            ];
        }
    }
    
    /**
     * update Updates the Payment Report View.
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
                "success" => 'Payment Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $paymentRepository = new PaymentRepository();

        $iv =  $this->model->find($id);
        return $paymentRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, $pagination, 'payment_view_id', 'payment_listing');
    }

    
}