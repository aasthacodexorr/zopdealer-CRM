<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\VoucherViewModel;

/**
 * VoucherViewRepository CRUD and complex queries for VoucherView Model
 */
class VoucherViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new VoucherViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
       
    }

    /**
     * creates new Voucher View.
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
                "success" => 'New Voucher View Created',
                "id" => $vendor_view_id
            ];
        }
    }
    
    /**
     * update Updates the Voucher View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id) : array
    {

        $voucherView = new Entity($data);
        $voucherView->id = $id;
        
        if ($this->model->save($voucherView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Voucher View Response has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $voucherRepository = new VoucherRepository();
        
        $iv =  $this->model->find($id);
        return $voucherRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort,true,'voucher_view_id','voucher_listing' );

    }

    
}