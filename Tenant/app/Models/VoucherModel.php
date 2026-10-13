<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class VoucherModel extends ExactSearchableModel
{
    protected $table      = 'vouchers';
    protected $primaryKey = 'id';

    protected $searchableColumns = [];

    protected $allowedFields = [
        'title', 'description', 'disclosure', 'expiry_date',
        'template', 'status', 'module',
        'module_id', 'created_at', 'updated_at', 'deleted_at',
        'search_keyword', 'value'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\VoucherTemplate';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title' => [
            "label" => 'Title',
            "rules" => 'required'
        ],
        'expiry_date'  => [
            "label" => 'Expiry Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'template'  => [
            "label" => 'Template',
            "rules" => 'required'
        ],
        'module'  => [
            "label" => 'Module',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'module_id'  => [
            "label" => 'Module Id',
            "rules" => 'permit_empty|is_natural'
        ],
        'value' => [
            "label" => 'Value',
            "rules" => 'permit_empty|decimal'
        ]

    ];

    /**
     * findAll Overidding findAll method and returning the objects with Images Array.
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
            ->select("
            vouchers.*,
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
            GROUP_CONCAT( DISTINCT inventory.odometer, SPACE(2), inventory.km_miles ) as odometer,
              ")
            ->join('deals', 'deals.id = vouchers.module_id', 'inner')
            ->join('deal_customer_pivot_table', 'deal_customer_pivot_table.deal_id = deals.id AND deal_customer_pivot_table.customer_type="primary_customer"', 'left')
            ->join('customers', 'customers.id = deal_customer_pivot_table.customer_id', 'left')
            ->join('deal_inventory_pivot_table', 'deal_inventory_pivot_table.deal_id = deals.id', 'left')
            ->join('inventory', 'inventory.stock_no = deal_inventory_pivot_table.stock_no', 'left')
            ->groupBy('vouchers.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
