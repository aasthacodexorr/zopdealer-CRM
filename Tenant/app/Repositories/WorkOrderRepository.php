<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\CustomerModel;
use Tenant\app\Models\VendorModel;
use Tenant\app\Models\WorkOrderModel;

/**
 * WorkOrderRepository CRUD and pagination for the WorkOrder
 */
class WorkOrderRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new WorkOrderModel());
        $this->entity = 'Tenant\app\Entities\WorkOrder';
        $this->entityLabel = 'Work Order';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'work_order_id';
    }

    /**
     * @method prepareDataForFilter : prepares data received.
     * @param $data : $data received.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if ($key == 'zm_assigned_mechanic' || $key == 'zm_assigned_advisor') {
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

                    //TODO : Change the key array
                } else if ($key == 'zm_type' || $key == 'zm_status') {
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
            $builder = $db->table('work_orders');

            parent::setModel(new WorkOrderModel());

            $all_work_orders = NULL;
            if (empty($ids)) {
                $all_work_orders = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_work_orders = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_work_orders as $key => $value) {
                try {
                    $data['data'] = $value;
                    $work_order_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $repository = new WorkOrderCommonDependentRepository();
                        $customers = $repository->switchWorkOrderCommonDependentState('customer')->findByWorkOrderId($value['id']);

                        if (!empty($customers)) {
                            foreach ($customers as $key => $customer) {
                                $customer_details['data'] = (new CustomerRepository())->show($customer['customer_id']);
                                $customer_search_keyword = (new CustomerModel())->createKeyWordString($customer_details);
                                $work_order_with_updated_search_index['data']['search_keyword'] .= " " . $customer_search_keyword['data']['search_keyword'];
                            }
                        }
                        $inventories = $repository->switchWorkOrderCommonDependentState('inventory')->findByWorkOrderId($value['id']);
                        if (!empty($inventories)) {
                            foreach ($inventories as $key => $inventory) {
                                $inventory_details = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);
                                $work_order_with_updated_search_index['data']['search_keyword'] .= " " . strtolower($inventory_details['stock_no'] . ' ' . $inventory_details['vin']);
                            }
                        }
                        $builder->where('id', $value['id'])->set(['search_keyword' => $work_order_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }

        return ['success' => 'Work Order search index has been updated.'];
    }
}
