<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Repositories\ReviewRepository;

class ReviewController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the ReviewRepository which can be used by any method here.
     *
     * @var ReviewRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ReviewRepository();
    }

    public function index()
    {


        $items = 100;
        $paginate = true;

        $column = 'created_at';
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

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'review_view_id', 'review_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {

        $data = $this->request->getPost();
        $validated = true;
        $response = [];

        if (!array_key_exists('profile_photo_url', $this->request->getFiles())) {
            //save details
            $response = $this->repository->create($data);
        } else {
            $validated = $this->validate([
                'profile_photo_url' => [
                    'uploaded[profile_photo_url]',
                    'mime_in[profile_photo_url,application/pdf,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[profile_photo_url,32000]',
                ],
            ]);

            if ($validated) {
                //save file
                $file = $this->request->getFile('profile_photo_url');
                $name = $file->getRandomName();

                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                $data['profile_photo_url'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;
                $data['profile_photo_url'] = Utility::getImageUrl($data['profile_photo_url']);

                //save details
                $response = $this->repository->create($data);
            } else {

                //throw error
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
                exit;
            }
        }

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {

        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Review couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $review = $this->repository->show($id);

        if ($review == null || !isset($review['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Review couldn't be found."
            ])->send();
        } else {
            $data = $this->request->getPost();

            $response = $this->repository->update($id, $data);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        }
    }

    public function updates()
    {

        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("review_ids", $columns)) {
            unset($columns['review_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $review_ids = $data['review_ids'];
        $data['review_ids'] = explode(',', $data['review_ids']);

        if (count($data['review_ids']) > 0 && count($columns) > 0) {

            $this->repository->updates($data['review_ids'], $columns);
            (new ReviewRepository())->updateSearchIndex($data['review_ids']);

            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (!empty($data['review_ids'])) {

            $data['review_ids'] = explode(',', $data['review_ids']);

            if (count($data['review_ids']) > 0) {
                $this->repository->delete($data['review_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }
}
