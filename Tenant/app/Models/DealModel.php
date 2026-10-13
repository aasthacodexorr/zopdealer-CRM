<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class DealModel extends ExactSearchableModel
{
    protected $table      = 'deals';
    protected $primaryKey = 'id';
    protected $searchableColumns = [
        'status'
    ];

    protected $allowedFields = [
        'type', 'date', 'retail_or_wholesale_flag', 'status', 'delivery_date_time',
        'disclosure', 'condition', 'brand', 'certified',
        'note', 'approved_by', 'salesperson', 'close_date', 'phonetic_string',
        'created_at', 'updated_at', 'deleted_at', 'cancellation_date', 'search_keyword',
        'report_status'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Deal';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'type'  => [
            "label" => 'Type',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'retail_or_wholesale_flag'  => [
            "label" => 'Retail or Wholesale Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        'status'  => [
            "label" => 'Status',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'approved_by'  => [
            "label" => 'Approved By',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'salesperson'  => [
            "label" => 'Salesperson',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
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
                "deals.* ,
            GROUP_CONCAT( DISTINCT customers.first_name ) as first_name,
            GROUP_CONCAT( DISTINCT customers.last_name) as last_name,
            GROUP_CONCAT( DISTINCT customers.phone ) as phone,
            GROUP_CONCAT( DISTINCT customers.mobile ) as mobile,
            GROUP_CONCAT( DISTINCT customers.email ) as email,
            GROUP_CONCAT( DISTINCT customers.company_name ) as company_name,
             
            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin,
            GROUP_CONCAT( DISTINCT inventory.stock_no ) as stock_no,
            GROUP_CONCAT( DISTINCT inventory.odometer, SPACE(2), inventory.km_miles ) as odometer,
            GROUP_CONCAT( DISTINCT inventory.exterior_color ) as exterior_color,
            GROUP_CONCAT( DISTINCT inventory.interior_color ) as interior_color
             "
            )
            ->join('deal_customer_pivot_table', 'deal_customer_pivot_table.deal_id = deals.id AND deal_customer_pivot_table.customer_type="primary_customer"', 'left')
            ->join('customers', 'customers.id = deal_customer_pivot_table.customer_id', 'left')
            ->join('deal_inventory_pivot_table', 'deal_inventory_pivot_table.deal_id = deals.id' ,'left')
            ->join('inventory', 'inventory.stock_no = deal_inventory_pivot_table.stock_no', 'left')
            ->groupBy('deals.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
