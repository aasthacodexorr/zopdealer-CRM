<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Models\FacebookMarketPlaceModel;
use Carbon\Carbon;
use Tenant\app\Models\CustomerModel;
use Tenant\app\Models\ReviewFeedbackModel;

/**
 * ReviewFeedbackRepository CRUD and pagination for the Review Feedback Modal
 */
class ReviewFeedbackRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new ReviewFeedbackModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Review Feedback';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'review_feedback_id';
    }

    /**
     * @method prepareDataForFilter : prepares data recieved.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (false) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                } elseif ($key == 'zm_response' || $key == 'zm_status') {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Single Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                }
            }
        }

        return json_encode($query);
    }

    public function createOrUpdate(array $data): array
    {
        try {
            if (empty($data['customer_id'])) {
                throw new \Exception("Customer ID is missing.", 1);
            }

            $instance = $this->model->where('customer_id', $data['customer_id'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {
            return ["errors" => $e->getMessage()];
        }
    }

    public function findByCustomerId($customer_id)
    {
        return $this->model->where('customer_id', $customer_id)->asArray()->first();
    }

    /**
     * updateSearchIndex
     *
     * @param  mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {

        try {

            $db = \Config\Database::connect();
            $builder = $db->table('review_feedbacks');

            parent::setModel(new ReviewFeedbackModel());

            $all_review_feedbacks = NULL;
            if (empty($ids)) {
                $all_review_feedbacks = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_review_feedbacks = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_review_feedbacks as $key => $value) {
                try {
                    $data['data'] = $value;
                    $review_feedback_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $repository = get_called_class();
                        $repository = new $repository;

                        if (!empty($value['customer_id'])) {
                            $customer_details['data'] = (new CustomerRepository())->show($value['customer_id']);
                            $customer_search_keyword = (new CustomerModel())->createKeyWordString($customer_details);
                            $review_feedback_with_updated_search_index['data']['search_keyword'] .= " " . $customer_search_keyword['data']['search_keyword'];
                        }

                        $builder->where('id', $value['id'])->set(['search_keyword' => $review_feedback_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }

        return ['success' => 'Deal search index has been updated.'];
    }
}
