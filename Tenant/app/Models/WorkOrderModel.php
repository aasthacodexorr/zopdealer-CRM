<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class WorkOrderModel extends ExactSearchableModel
{
    protected $table      = 'work_orders';
    protected $primaryKey = 'id';
    protected $searchableColumns = [
        'status', 'assigned_mechanic'
    ];

    protected $allowedFields = [
        'status', 'auto_sync_expense_flag', 'type', 'check_in_date_time', 'check_out_date_time',
        'check_in_odometer', 'check_out_odometer', 'assigned_mechanic', 'assigned_advisor',
        'created_at', 'updated_at', 'deleted_at', 'search_keyword'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\WorkOrder';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'status'  => [
            "label" => 'Status',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'auto_sync_expense_flag'  => [
            "label" => 'Auto Sync Expense',
            "rules" => 'permit_empty|is_natural'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'check_in_odometer'                        => [
            "label" => 'Check In Odometer',
            "rules" => 'permit_empty|is_natural'
        ],
        'check_out_odometer'                        => [
            "label" => 'Check Out Odometer',
            "rules" => 'permit_empty|is_natural'
        ],
        'assigned_mechanic'  => [
            "label" => 'Assigned Mechanic',
            "rules" => 'permit_empty'
        ],
        'assigned_advisor'  => [
            "label" => 'Assigned Advisor',
            "rules" => 'permit_empty'
        ]
    ];

    /**
     * findAll Overidding findAll method and returning the objects with Customer Name, Phone Number, Email
     *
     * @param  mixed $limit
     * @param  mixed $offset
     * @return void
     */
    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder
            ->select(
                "work_orders.* ,
                work_orders.id as work_order_id,
                GROUP_CONCAT( DISTINCT customers.first_name ) as first_name,
                GROUP_CONCAT( DISTINCT customers.last_name) as last_name,
                GROUP_CONCAT( DISTINCT customers.phone ) as phone,
                GROUP_CONCAT( DISTINCT customers.mobile ) as mobile,
                GROUP_CONCAT( DISTINCT customers.email ) as email,
                    
                GROUP_CONCAT( DISTINCT inventory.year ) as year,
                GROUP_CONCAT( DISTINCT inventory.make ) as make,
                GROUP_CONCAT( DISTINCT inventory.model ) as model,
                GROUP_CONCAT( DISTINCT inventory.vin ) as vin,
                GROUP_CONCAT( DISTINCT inventory.stock_no ) as stock_no,
             "
            )
            ->join('work_order_customer_pivot_table', 'work_order_customer_pivot_table.work_order_id = work_orders.id', 'left')
            ->join('customers', 'customers.id = work_order_customer_pivot_table.customer_id', 'left')
            ->join('work_order_inventory_pivot_table', 'work_order_inventory_pivot_table.work_order_id = work_orders.id', 'left')
            ->join('inventory', 'inventory.stock_no = work_order_inventory_pivot_table.stock_no', 'left')
            ->groupBy('work_orders.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
