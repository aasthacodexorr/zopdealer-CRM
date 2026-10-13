<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\ReviewModel;

/**
 * ReviewRepository CRUD and pagination for the Review
 */
class ReviewRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new ReviewModel());
        $this->entity = 'Tenant\app\Entities\Review';
        $this->entityLabel = 'Review';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'review_id';
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
            foreach ($data as $key => $value) {

                if ($key == 'zm_rating') {
                    $column = str_replace('zm_', '', $key);
                    $join = 'AND';
                    $value_2 = 1;

                    $single_query = [
                        'header' => null,
                        'column' => $column,
                        'join' => $join,
                        'comparator' => "between",
                        "type" => 'Numeric Range Comparision',
                        'value_1' => $value,
                        'value_2' => $value_2,
                    ];

                    array_push($query, $single_query);
                } else {
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
                }
            }
        }

        return json_encode($query);
    }

    public function allColumnAggregation($query)
    {
        $columns = [
            ['name' => 'source', 'type' => null],
            ['name' => 'rating', 'type' => 'numeric'],

        ];

        $result = [];
        foreach ($columns as $column) {
            if ($column['type'] == 'numeric') {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy(ABS($column['name']), 'ASC')->get()->getResultArray();
            } else {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy($column['name'], 'ASC')->get()->getResultArray();
            }
        }

        return $result;
    }

    public function createOrUpdate(array $data): array
    {
        try {

            if (empty($data['uuid'])) {
                throw new \Exception("UUID Missing.", 1);
            }

            $instance = $this->model->where('uuid', $data['uuid'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {

            return ["errors" => $e->getMessage()];
        }
    }

    /**
     * deleteWithoutKey
     *
     * @return void
     */
    public function deleteWithoutKey()
    {
        $this->model->delete();
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
            $builder = $db->table('reviews');

            $all_reviews = NULL;

            parent::setModel(new ReviewModel());

            if (empty($ids)) {
                $all_reviews =  $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_reviews = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_reviews as $key => $value) {
                $data['data'] = $value;
                $review_with_updated_search_index = $this->model->createKeyWordString($data);
                if (isset($value['id'])) {
                    $builder->where('id', $value['id'])->set(['search_keyword' => $review_with_updated_search_index['data']['search_keyword']])->update();
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Review search index has been updated.'];
    }
}
