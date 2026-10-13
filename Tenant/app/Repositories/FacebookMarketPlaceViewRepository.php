<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\VendorViewModel;
use Tenant\app\Entities\VendorView;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Models\FacebookMarketPlaceViewModel;
use Tenant\app\Repositories\VendorRepository;

/**
 * FacebookMarketPlaceViewRepository CRUD and complex queries for FacebookMarketPlace Model
 */
class FacebookMarketPlaceViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new FacebookMarketPlaceViewModel());
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

        if ($this->model->save(new VendorView($data)) === false) {
            return $this->model->errors();
        } else {
            $facebook_marketplace_view_id = $this->model->insertID;
            return [
                "success" => 'New FBMP View Created',
                "id" => $facebook_marketplace_view_id
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
                "success" => 'FBMP View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $facebookMarketPlaceRepository = new FacebookMarketPlaceRepository();
        $facebookMarketPlaceRepository->setModel(new FacebookMarketplaceListModel());

        $iv =  $this->model->find($id);
        return $facebookMarketPlaceRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'facebook_marketplace_view_id', 'facebook_marketplace_listing');
    }
}
