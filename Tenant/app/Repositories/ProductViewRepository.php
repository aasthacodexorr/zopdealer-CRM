<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\ProductViewModel;

/**
 * ProductViewRepository CRUD and complex queries for ProductView Model
 */
class ProductViewRepository extends BaseRepository
{

    public function __construct(){
        parent::__construct(new ProductViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
       
    }

    /**
     * creates new Product View.
     *
     * @return array
     */
    public function create($data) : array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors() ;
            
        } else {
            $product_view_id = $this->model->insertID;
            return [
                "success" => 'New Product View Created',
                "id" => $product_view_id
            ];
        }
    }
    
    /**
     * update Updates the Product View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id) : array
    {

        $productView = new Entity($data);
        $productView->id = $id;
        
        if ($this->model->save($productView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Product View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if($this->request->getGet('items')){
            $items = $this->request->getGet('items');
        }
        $productRepository = new ProductRepository();
        
        $iv =  $this->model->find($id);
        return $productRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view,$query = $iv->query, $items, $iv->sort,true,'product_view_id','product_listing' );

    }

    
}