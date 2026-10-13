<?php

namespace App\Repositories;

use App\Models\KellyBlueBookModel;
use App\Models\WordpressJobModel;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Tenant\app\Entities\DealView;

/**
 * KellyBlueBookRepository CRUD for wordpressJobView Model
 */
class KellyBlueBookRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new KellyBlueBookModel());
        $this->entity = '';
        $this->entityLabel = 'Kelly Blue Book Inventory';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'kelly_blue_book_id';
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the website to make search consistent.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_year' || $key == 'zm_model' ||
                    $key == 'zm_make' || $key == 'zm_trim'

                ) {
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

    public function distinctColumn($column, $query)
    {
        $direction = $column == 'year' ? 'DESC' : 'ASC';
        $this->allQueries($query);
        $result = $this->model->select($column)
            ->distinct()
            ->limit(500)
            ->orderBy($column, $direction)
            ->get()
            ->getResultArray();

        return array_column($result, $column);
    }
}
