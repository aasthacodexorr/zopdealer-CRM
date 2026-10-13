<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Models\TestDriveCustomerViewModel;
use Tenant\app\Entities\TestDriveCustomerView;
use Tenant\app\Repositories\TestDriveCustomerRepository;

/**
 * TestDrivesCustomerViewRepository CRUD and complex queries for TestDrivesCustomerView Model
 */
class TestDriveCustomerViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new TestDriveCustomerViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();

    }

    /**
     * creates new Test Drives Customer View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new TestDriveCustomerView($data)) === false) {
            return $this->model->errors();

        } else {
            $test_drive_customer_view_id = $this->model->insertID;
            return [
                "success" => 'New Test Drives Customer View Created',
                "id" => $test_drive_customer_view_id
            ];
        }
    }

    /**
     * update Updates the Test Drives Customer View.
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $testDriveCustomerView = new TestDriveCustomerView($data);
        $testDriveCustomerView->id = $id;

        if ($this->model->save($testDriveCustomerView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Test Drives Customer View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $testDriveCustomerRepository = new TestDriveCustomerRepository();

        $iv = $this->model->find($id);

        return $testDriveCustomerRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'test_drives_customers_view_id', 'test_drives_customers_listing');
    }
}