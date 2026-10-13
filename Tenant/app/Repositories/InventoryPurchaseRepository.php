<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\InventoryPurchaseModel;

/**
 * InventoryPurchaseRepository CRUD and pagination for the Inventory Purchase
 * 
 */
class InventoryPurchaseRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new InventoryPurchaseModel());
        $this->entity = 'Tenant\app\Entities\InventoryPurchase';
        $this->entityLabel = 'Inventory Purchase';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'inventory_purchase_id';
        $this->audit_log_module = 'inventory';
        $this->audit_entity_type = 'inventory_purchase';
        $this->audit_sub_module = 'inventory_purchase';
    }

    /**
     * findByInventoryId search Inventory Purchase By Id
     *
     * @param  mixed $inventory_id
     */
    public function findByInventoryId($inventory_id)
    {
        return $this->model->where('inventory_id', $inventory_id)->asArray()->first();
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


    public function prepareDataForFilter($data)
    {
        $query = [];

        $multi_select_keys = ['company_name', 'lien_holder_name'];
        $single_select_keys = ['has_been_paid_for', 'has_title_been_received', 'has_title_been_transferred', 'has_lien', 'lien_has_been_paid'];
        $date_comparison_keys = ['purchase_date', 'payment_date', 'title_transfer_date', 'title_received_date', 'lien_paid_date'];
        $numeric_comparison_keys = ['price', 'lien_amount'];


        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                $key = str_replace('zm_', '', $key);
                if (
                    in_array($key, $multi_select_keys)
                ) {
                    $single_query = [
                        'header' => null,
                        'column' => $key,
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                } else if (
                    in_array($key, $single_select_keys)
                ) {
                    $single_query = [
                        'header' => null,
                        'column' => $key,
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Single Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                }
            }



            foreach ($date_comparison_keys as $key) {
                if (!empty($data['zm_' . $key . '_start']) && !empty($data['zm_' . $key . '_end'])) {
                    $single_query = [
                        'header' => null,
                        'column' => $key,
                        'join' => 'AND',
                        'comparator' => "between",
                        "type" => "Date Comparision",
                        'value_1' => array_shift($data['zm_' . $key . '_start']),
                        'value_2' => array_shift($data['zm_' . $key . '_end']),
                    ];
                    array_push($query, $single_query);
                }
            }

            foreach ($numeric_comparison_keys as $key) {
                if (!empty($data['zm_' . $key . '_start']) && !empty($data['zm_' . $key . '_end'])) {
                    $single_query = [
                        'header' => null,
                        'column' => $key,
                        'join' => 'AND',
                        'comparator' => "between",
                        "type" => "Numeric Comparision",
                        'value_1' => array_shift($data['zm_' . $key . '_start']),
                        'value_2' => array_shift($data['zm_' . $key . '_end']),
                    ];
                    array_push($query, $single_query);
                }
            }
        }

        return json_encode($query);
    }
}
