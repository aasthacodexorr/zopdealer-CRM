<?php
namespace Cardora\app\Controllers;

use Cardora\app\Repositories\CinventoryRepository;

class CinventoryController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the CinventoryRepository which can be used by any method here.
     *
     * @var CinventoryRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new CinventoryRepository();
    }

    public function index()
    {
        $items = 25;
        $paginate = true;

        $column = '';  //carinv_id
        $order = 'DESC';

        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }

        $data = $this->repository;
        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $data = $data->index(null, $header = null, null,  $query, $items, $sort, $paginate, 'inventory_view_id', 'inventory_listing');
        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function show($id)
    {
        $response = $this->repository->show($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }


    public function filters()
    {
        $query = [];
        $result = [];
    //  $search = '';
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        // if ($this->request->getGet('search')) {
        //     $search = $this->request->getGet('search');
        // }

       $result = $this->repository->allColumnAggregation($query);      
        $this->response->setContentType('application/json')->setJSON($result)->send();
    }
}