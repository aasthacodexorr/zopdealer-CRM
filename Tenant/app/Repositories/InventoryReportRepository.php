<?php

namespace Tenant\app\Repositories;

use Tenant\app\Models\InventoryReportModel;

/**
 * InventoryReportRepository This class consist of filters for Inventory
 */
class InventoryReportRepository extends InventoryRepository
{
    public function __construct()
    {
        $this->setModel(new InventoryReportModel());
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
                if (
                    $key == 'zm_status' ||  $key == 'zm_vehicle_type'
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
                } else if ($key == 'zm_visibility' || $key == 'zm_feature_listing') {
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


            if (!empty($data['zm_date_start']) && !empty($data['zm_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "stock_date",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }

    public function findByInventoryId($inventory_id)
    {
        return $this->model->where('inventory.inventory_id', $inventory_id)->asArray()->findAll();
    }
}
