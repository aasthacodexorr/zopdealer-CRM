<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryCostModel;
use Tenant\app\Entities\InventoryCost;
use Carbon\Carbon;

/**
 * InventoryCostRepository CRUD and pagination for the Inventory Cost
 * 
 */
class InventoryCostRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryCostModel());
        $this->entity = 'Tenant\app\Entities\InventoryCost';
        $this->entityLabel = 'Inventory Cost';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_cost_id';
    }

    /**
     * findByInventoryId search Inventory Cost By Id
     *
     * @param  mixed $inventory_id
     */
    public function findByInventoryId($inventory_id)
    {
        return $this->model->where('inventory_id', $inventory_id)->orderBy('created_at', 'DESC')->asArray()->findAll();
    }

    /**
     * deleteByInventoryId
     *
     * @param  string $inventory_id
     * @return void
     */
    public function deleteByInventoryId($inventory_id)
    {
        if (!empty($inventory_id)) {
            $this->model->where('inventory_id', $inventory_id)->delete();
        }
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
                    $key == 'zm_invoice'   ||
                    $key == 'zm_vendor_name'   ||
                    $key == 'zm_name'

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

            if (!empty($data['zm_purchase_date_start']) && !empty($data['zm_purchase_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "purchase_date",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_purchase_date_start']),
                    'value_2' => array_shift($data['zm_purchase_date_end']),
                ];
                array_push($query, $single_query);
            }

            if (!empty($data['zm_sub_total_start']) && !empty($data['zm_sub_total_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "sub_total",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Numeric Comparision",
                    'value_1' => array_shift($data['zm_sub_total_start']),
                    'value_2' => array_shift($data['zm_sub_total_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }
}
