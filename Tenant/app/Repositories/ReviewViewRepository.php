<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\ReviewViewModel;

/**
 * ReviewViewRepository CRUD and complex queries for ReviewView Model
 */
class ReviewViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new ReviewViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    /**
     * show This shows single instance of entity
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * creates new Review View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors();
        } else {
            $review_view_id = $this->model->insertID;
            return [
                "success" => 'New Review View Created',
                "id" => $review_view_id
            ];
        }
    }

    /**
     * update Updates the Review View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $reviewView = new Entity($data);
        $reviewView->id = $id;

        if ($this->model->save($reviewView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Review View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $reviewRepository = new ReviewRepository();

        $iv =  $this->model->find($id);
        return $reviewRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'review_view_id', 'review_listing');
    }
    
    /**
     * renderView
     *
     * @param  mixed $query
     * @param  mixed $paginate
     * @return 
     */
    public function renderView($query, bool $paginate = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $reviewRepository = new ReviewRepository();
        return $reviewRepository->index(null, $header = null, $view = null, $query, $items, '{"review_date_time" : "DESC"}', $paginate, 'review_view_id', 'review_listing');
    }
}
