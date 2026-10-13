<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Models\TestDriveViewModel;
use Tenant\app\Entities\TestDriveView;
use Tenant\app\Repositories\TestDriveRepository;

/**
 * TestDriveViewRepository CRUD and complex queries for TestDriveView Model
 */
class TestDriveViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new TestDriveViewModel());
    }

    /**
     * creates new Test Drive View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new TestDriveView($data)) === false) {
            return $this->model->errors();

        } else {
            $test_drive_view_id = $this->model->insertID;
            return [
                "success" => 'New Test Drive View Created',
                "id" => $test_drive_view_id
            ];
        }
    }

    /**
     * update Updates the Test Drive View.
     *
     * @param mixed $data
     * @param mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $testDriveView = new TestDriveView($data);
        $testDriveView->id = $id;

        if ($this->model->save($testDriveView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Test Drive View Response has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $testDriveRepository = new TestDriveRepository();

        $iv = $this->model->find($id);

        // Check if view record exists
        if (!$iv) {
            return [
                'error' => 'Test drive view not found',
                'test_drive_view_id' => $id,
                'test_drive_listing' => [],
                'pager' => null
            ];
        }

        return $testDriveRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'test_drive_view_id', 'test_drive_listing');
    }


}

