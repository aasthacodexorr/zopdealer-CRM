<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\ReviewFeedbackListModel;
use Tenant\app\Models\ReviewFeedbackViewModel;

/**
 * ReviewFeedbackViewRepository CRUD and complex queries for ReviewFeedback Model
 */
class ReviewFeedbackViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new ReviewFeedbackViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    /**
     * creates new Review Feedback View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors();
        } else {
            $review_feedback_id = $this->model->insertID;
            return [
                "success" => 'New Review Feedback View Created',
                "id" => $review_feedback_id
            ];
        }
    }

    /**
     * update Updates the Review Feedback View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $review_feedback_view = new Entity($data);
        $review_feedback_view->id = $id;

        if ($this->model->save($review_feedback_view) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Review Feedback View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $review_feeback_repository = new ReviewFeedbackRepository();
        $review_feeback_repository->setModel(new ReviewFeedbackListModel());

        $iv =  $this->model->find($id);
        return $review_feeback_repository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'review_feedback_view_id', 'review_feedback_listing');
    }
}
