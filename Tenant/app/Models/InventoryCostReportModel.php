<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;

class InventoryCostReportModel extends SearchableModel
{
    protected $table      = 'inventory_costs';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'name',
        'vendor_name',
        'price'
    ];

    protected $allowedFields = [
        'name',
        'description',
        'invoice',
        'vendor_name',
        'price',
        'quantity',
        'discount',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'sub_total',
        'purchase_date',
        'inventory_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'phonetic_string',
        'tax_type_secondary',
        'tax_amount_secondary',
        'tax_percentage_secondary',
        'tax_amount_total',
        'payment_reference'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\InventoryCost';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'  => [
            "label" => 'Name',
            "rules" => 'required'
        ],
        'description' => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'invoice' => [
            "label" => 'Invoice',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'payment_reference' => [
            "label" => 'Payment Reference',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'price' => [
            "label" => 'Price',
            "rules" => 'permit_empty|decimal'
        ],
        'quantity'  => [
            "label" => 'Quantity',
            "rules" => 'permit_empty|is_natural'
        ],
        'discount' => [
            "label" => 'Discount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_percentage' => [
            "label" => 'Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount' => [
            "label" => 'Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'sub_total' => [
            "label" => 'Sub Total',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_percentage_secondary' => [
            "label" => 'Secondary Tax Percentage',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],


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
            ->select("inventory_costs.*,
            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.trim ) as trim,
            GROUP_CONCAT( DISTINCT inventory.stock_no ) as stock_no,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin
              ")
            ->join('inventory', 'inventory_costs.inventory_id = inventory.inventory_id', 'left')
            ->groupBy('inventory_costs.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
