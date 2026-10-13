<?php

namespace Tenant\app\Repositories;

use App\Entities\MasterEntity;
use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\VendorViewModel;
use Tenant\app\Entities\VendorView;
use Tenant\app\Models\CraigslistListModel;
use Tenant\app\Models\CraigslistViewModel;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Models\FacebookMarketPlaceViewModel;
use Tenant\app\Repositories\VendorRepository;

/**
 * CraigslistViewRepository CRUD and complex queries for Craigslist View Model
 */
class CraigslistViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new CraigslistViewModel());
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
    public function create($data): array
    {

        if ($this->model->save(new MasterEntity($data)) === false) {
            return $this->model->errors();
        } else {
            $craigslist_view_id = $this->model->insertID;
            return [
                "success" => 'New Craigslist View Created',
                "id" => $craigslist_view_id
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
    public function update($data, $id): array
    {

        $fbmpView = new Entity($data);
        $fbmpView->id = $id;

        if ($this->model->save($fbmpView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Craigslist View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $craigslistRepository = new CraigslistRepository();
        $craigslistRepository->setModel(new CraigslistListModel());

        $iv =  $this->model->find($id);
        return $craigslistRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'craigslist_view_id', 'craigslist_listing');
    }
}
