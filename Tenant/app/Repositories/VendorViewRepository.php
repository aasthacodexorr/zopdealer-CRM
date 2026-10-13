<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Models\VendorViewModel;
use Tenant\app\Entities\VendorView;
use Tenant\app\Repositories\VendorRepository;

/**
 * VendorViewRepository CRUD and complex queries for VendorView Model
 */
class VendorViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new VendorViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
       
    }

    /**
     * creates new Vendor View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new VendorView($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $vendor_view_id = $this->model->insertID;
            return [
                "success" => 'New Vendor View Created',
                "id" => $vendor_view_id
            ];
        }
    }
    
    /**
     * update Updates the Vendor View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id) : array
    {

        $vendorView = new VendorView($data);
        $vendorView->id = $id;
        
        if ($this->model->save($vendorView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Vendor View Response has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $vendorRepository = new VendorRepository();
        
        $iv =  $this->model->find($id);
        return $vendorRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort,true,'vendor_view_id','vendor_listing' );

    }

    
}