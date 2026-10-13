<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\SocialConnectListModel;
use Tenant\app\Models\SocialConnectViewModel;

/**
 * SocialConnectViewRepository CRUD and complex queries for FacebookConnect Model
 */
class SocialConnectViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new SocialConnectViewModel());
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
            $facebook_connect_view_id = $this->model->insertID;
            return [
                "success" => 'New Social View Created',
                "id" => $facebook_connect_view_id
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
                "success" => 'Social View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $facebookConnectRepository = new SocialConnectRepository();
        $facebookConnectRepository->setModel(new SocialConnectListModel());

        $iv =  $this->model->find($id);
        return $facebookConnectRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'social_connect_view_id', 'social_connect_listing');
    }
}
