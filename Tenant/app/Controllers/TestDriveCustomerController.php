<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\TestDriveCustomerRepository;
use App\Libraries\CloudStorage\CloudStorageFactory;
use Carbon\Carbon;

class TestDriveCustomerController extends \App\Controllers\BaseController
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new TestDriveCustomerRepository();
    }

    /**
     * List all test drive customers
     */
    public function index()
    {
        $items = $this->request->getGet('items') ?? 100;
        $paginate = true;
        $column = 'created_at';
        $order = 'DESC';

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate = $paginate == 'false' ? false : true;
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
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order . '"}';
        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'test_drives_customers_view_id', 'test_drives_customers_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    /**
     * Create a new test drive customer
     */
    public function create()
    {
        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            return;
        }

        $data = $this->request->getPost();

        // 1. Upload license_front image and store its URL
        $licenseFrontFile = $this->request->getFile('license_front');
        if ($licenseFrontFile && $licenseFrontFile->isValid()) {
            $fileName = $licenseFrontFile->getRandomName();
            $storagePath = ROOT_IMAGE . '/' . md5(db_connect()->database) . '/test-drive-customers/licenses/' . $fileName;
            try {
                CloudStorageFactory::getInstance()->save($licenseFrontFile, $storagePath);
                $data['license_front'] = '/' . $storagePath;
            } catch (\Exception $e) {
                $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['success' => false, 'errors' => ['license_front' => 'Failed to upload license front image: ' . $e->getMessage()]])->send();
                return;
            }
        }

        // 2. Upload license_back image and store its URL
        $licenseBackFile = $this->request->getFile('license_back');
        if ($licenseBackFile && $licenseBackFile->isValid()) {
            $fileName = $licenseBackFile->getRandomName();
            $storagePath = ROOT_IMAGE . '/' . md5(db_connect()->database) . '/test-drive-customers/licenses/' . $fileName;
            try {
                CloudStorageFactory::getInstance()->save($licenseBackFile, $storagePath);
                $data['license_back'] = '/' . $storagePath;
            } catch (\Exception $e) {
                $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['success' => false, 'errors' => ['license_back' => 'Failed to upload license back image: ' . $e->getMessage()]])->send();
                return;
            }
        }

        // 3. Convert license_expiry to YYYY-MM-DD before saving
        try {
            if (!empty($data['license_expiry'])) {
                $data['license_expiry'] = Carbon::parse($data['license_expiry'])->format('Y-m-d');
            }
        } catch (\Exception $e) {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON(['success' => false, 'errors' => ['license_expiry' => 'Invalid date format provided. Expected a valid date string.']])->send();
            return;
        }

        // 4. Save — model validation rules cover all field requirements
        $response = $this->repository->create($data);

        if ($response['success']) {
            $this->repository->updateSearchIndex([$response['test_drives_customers_id']]);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Show a single customer
     */
    public function show($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = $this->repository->show($id);

            if (isset($response)) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Vendor couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    /**
     * Update a customer
     */
    public function update($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $customer = $this->repository->show($id);

            if ($customer == null || !isset($customer['id'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                    "errors" => "Test Drive Customer couldn't be found."
                ])->send();
            } else {
                $data = $this->request->getPost();

                $response = $this->repository->update($id, $data);
                if (isset($response['success'])) {
                    $this->repository->updateSearchIndex([$response['test_drives_customers_id']]);
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    /**
     * Delete a customer (soft delete)
     */
    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (service('authPolicy')->hasElevatedRoles() && !empty($data['test_drives_customers_ids'])) {
            $data['test_drives_customers_ids'] = explode(',', $data['test_drives_customers_ids']);
            if (count($data['test_drives_customers_ids']) > 0) {
                $this->repository->delete($data['test_drives_customers_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}