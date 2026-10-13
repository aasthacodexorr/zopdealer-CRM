<?php

namespace Tenant\app\Controllers;

use App\Helpers\TenantNonPersistantCache;
use Tenant\app\Repositories\LocationRepository;

/**
 * LocationController : CRUD Controller for managing the locations.
 */
class LocationController extends \App\Controllers\BaseController
{

    /**
     * repository This repository corresponds to the LocationRepository which can be used by any method here.
     *
     * @var LocationRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new LocationRepository();
    }

    public function index()
    {

        $items = 5;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';


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

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->search($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'location_view_id', 'location_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {

        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            if ($data['default'] == '1') {
                $this->repository->updateMultiple(['default' => 0], 'id !=', $response['location_id']);
            }

            $this->deleteRelatedCache();
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {

        $response = $this->repository->show($id);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function findByLocationNameOrDefault()
    {

        $data = $this->request->getPost();

        $validated = $this->validate([
            'name'  => [
                "label" => 'Location Name',
                "rules" => 'permit_empty'
            ],
        ]);
        if ($validated) {

            $response = $this->repository->getLocationByName($data['name']);
            if (!empty($response)) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Location could not be found'])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function update($id)
    {

        $data = $this->request->getPost();
        $available_location = $this->repository->show($id);

        if ($available_location == null || !isset($available_location['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Location couldn't be found."
            ])->send();
        } else {

            $data['name'] = $available_location['name'];
            $response = $this->repository->update($id, $data);
            if (isset($response['success'])) {
                if ($data['default'] == '1') {
                    $this->repository->updateMultiple(['default' => 0], 'id !=', $id);
                    $this->deleteRelatedCache();
                }
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        }
    }

    public function delete($id)
    {
        $this->repository->delete($id);
    }

    public function changeActivation($id)
    {
        $data = $this->request->getPost();
        if (isset($data['status'])) {
            if ($data['status'] != '0' && $data['status'] != '1') {
                $response = ['errors' => 'Status should either be 1 or 0.'];
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                exit;
            }
        } else {
            $response = ['errors' => 'Status is required.'];
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            exit;
        }
        $status = $data['status'];
        $this->deleteRelatedCache();
        $this->repository->updateMultiple(['status' => $status], 'id', $id);
    }

    public function changeDefault($id)
    {
        $data = $this->request->getPost();
        if (isset($data['default'])) {
            if ($data['default'] != '1') {
                $response = ['errors' => 'Default should be 1.'];
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                exit;
            }
        } else {
            $response = ['errors' => 'Default is required.'];
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            exit;
        }
        $default = $data['default'];
        $this->repository->updateMultiple(['default' => $default], 'id', $id);
        $this->repository->updateMultiple(['default' => !(int)$default], 'id !=', $id);
        $this->deleteRelatedCache();
    }

    public function getAllActiveLocation()
    {
        $response = $this->repository->filterData('status', 1)->index(null, $header = null, null, null, null, null, false, 'location_view_id', 'locations');
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    private function deleteRelatedCache()
    {
        $cache_key = 'phaeton_config';
        TenantNonPersistantCache::delete($cache_key);
    }
}
