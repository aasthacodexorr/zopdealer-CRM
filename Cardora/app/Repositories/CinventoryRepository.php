<?php

namespace Cardora\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Cardora\app\Models\CinventoryModel;

/**
 * CinventoryRepository CRUD and pagination for the Inventory
 */
class CinventoryRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase(getEnv('database.cardora.database'));

        parent::__construct(new CinventoryModel());
        $this->entity = 'Cardora\app\Entities\Cinventory';
        $this->entityLabel = 'Inventory';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'carinv_id';
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
                    $key == 'zm_body' || $key == 'zm_year' || $key == 'zm_fuel_type' || $key == 'zm_exterior_color' || $key == 'zm_model' ||
                    $key == 'zm_make' || $key == 'zm_transmission' || $key == 'zm_stockid'

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
                } else if ($key == 'zm_price' ) {
                    // foreach ($value as $combined_value) {
                    $column = str_replace('zm_', '', $key);
                    $join = 'AND';
                   // $value_2 = $key == 'zm_fuel_hwy' || $key == 'zm_fuel_city' ? 0.01 : 1;
                    // $value_1 =   explode('-', $combined_value)[0];
                    // $value_2 =   explode('-', $combined_value)[1];

                    $single_query = [
                        'header' => null,
                        'column' => $column,
                        'join' => $join,
                        'comparator' => "between",
                        "type" => 'Numeric Range Comparision',
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                    //}
                } else if ($key == "zm_dis") {
                    $single_query = [
                        'header' => null,
                        'column' => 'stock_date',
                        'join' => 'AND',
                        'comparator' => "less than",
                        "type" => "Relative Time Period Comparision",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                } else if ($key == "zm_feature_listing") {
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
 
    

 //TODO : Refactor allColumnAggregation and prepareDataForFilter to enable it to other Repositories
 public function allColumnAggregation($query, $status = false, $search = '')
 {
     $columns = [
         ['name' => 'make', 'type' => null],
         ['name' => 'model', 'type' => null],
         ['name' => 'year', 'type' => null],
         ['name' => 'price', 'type' => 'numeric'],
         ['name' => 'interior_color', 'type' => null],
         ['name' => 'exterior_color', 'type' => null],
         ['name' => 'body', 'type' => null],
         ['name' => 'transmission', 'type' => null],
         ['name' => 'fuel_type', 'type' => null],

     ];

 
     $result = [];
     foreach ($columns as $column) {
         if ($column['type'] == 'numeric') {
           
            //  if ($search != '') {
            //      $this->search($search);
            //  }
             $this->allQueries($query);
             $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->groupBy($column['name'])->orderBy(ABS($column['name']), 'ASC')->get()->getResultArray();
         } else {
           
            //  if ($search != '') {
            //      $this->search($search);
            //  }
             $this->allQueries($query);
             $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->groupBy($column['name'])->orderBy($column['name'], 'ASC')->get()->getResultArray();
         }
     }

     return $result;
 }
 
}