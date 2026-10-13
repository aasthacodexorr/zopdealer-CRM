<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;

class InventoryPurchaseReportModel extends SearchableModel
{
    protected $table      = 'inventory_purchase';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'purchase_date', 'purchase_from_flag',
        'company_name', 'first_name', 'last_name', 'rin', 'tax_number',
        'street_address', 'apt', 'city', 'province', 'postal_code',
        'country', 'phone', 'fax', 'mobile', 'email',
        'driver_licence', 'plate_number', 'rin_or_dl_flag', 'tax_override_flag',
        'price', 'discount', 'tax_amount', 'tax_percentage', 'tax_type', 'inventory_id',
        'created_at', 'updated_at', 'deleted_at',
        'tax_type_secondary', 'tax_amount_secondary', 'tax_percentage_secondary', 'tax_amount_total',
        'mvda', 'sales_person_registration', 'key_count',
        'has_been_paid_for', 'has_title_been_received', 'has_title_been_transferred',
        'payment_date', 'title_transfer_date', 'title_received_date',
        'has_lien', 'lien_amount', 'lien_holder_name', 'lien_has_been_paid', 'lien_paid_date','permit_number'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\InventoryPurchase';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'first_name'  => [
            "label" => 'First Name',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'last_name'  => [
            "label" => 'Last Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'rin'  => [
            "label" => 'RIN',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'tax_number'  => [
            "label" => 'Tax Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'street_address'  => [
            "label" => 'Street Address',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'apt'  => [
            "label" => 'Suite / Apt',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'city'  => [
            "label" => 'City',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'province'  => [
            "label" => 'Province',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'postal_code'  => [
            "label" => 'Postal Code',
            "rules" => 'permit_empty|alpha_numeric_space'
        ],
        'country'  => [
            "label" => 'Country',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'phone'  => [
            "label" => 'Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'fax'  => [
            "label" => 'Fax',
            "rules" => 'permit_empty|is_natural'
        ],
        'mobile'  => [
            "label" => 'Mobile',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'email'  => [
            "label" => 'Email',
            "rules" => 'permit_empty|valid_email'
        ],
        'inventory_id'  => [
            "label" => 'Fax',
            "rules" => 'required|is_natural'
        ],
        'price' => [
            "label" => 'Purchase Price',
            "rules" => 'permit_empty|decimal'
        ],
        'discount' => [
            "label" => 'Discount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount' => [
            "label" => 'Tax',
            "rules" => 'permit_empty|decimal'
        ],
        'rin_or_dl_flag'  => [
            "label" => 'RIN or Driver License Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        'tax_override_flag'  => [
            "label" => 'Tax Override Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        'driver_licence'  => [
            "label" => 'Driver Licence',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'plate_number'  => [
            "label" => 'Plate Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'tax_amount_total' => [
            "label" => 'Total Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'tax_amount_secondary' => [
            "label" => 'Secondary Tax Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'mvda'  => [
            "label" => 'MVDA',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'sales_person_registration'  => [
            "label" => 'Sales Person Registration',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'key_count'  => [
            "label" => 'Key Count',
            "rules" => 'permit_empty|is_natural'
        ],
        'has_been_paid_for'  => [
            "label" => 'Paid',
            "rules" => 'required|is_natural'
        ],
        'has_title_been_received'  => [
            "label" => 'Title has been received',
            "rules" => 'required|is_natural'
        ],
        'has_title_been_transferred'  => [
            "label" => 'Title has been transferred',
            "rules" => 'required|is_natural'
        ],
        'payment_date'  => [
            "label" => 'Payment Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'title_transfer_date'  => [
            "label" => 'Title Transfer Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'title_received_date'  => [
            "label" => 'Title Received Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'has_lien'  => [
            "label" => 'Has Lien',
            "rules" => 'required|is_natural'
        ],
        'lien_amount'  => [
            "label" => 'Lien Amount',
            "rules" => 'permit_empty|decimal'
        ],
        'lien_holder_name'  => [
            "label" => 'Lien Holder Name',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'lien_has_been_paid'  => [
            "label" => 'Lien Has Been Paid',
            "rules" => 'required|is_natural'
        ],
        'lien_paid_date'  => [
            "label" => 'Lien Paid Date',
            "rules" => 'permit_empty|valid_date'
        ],
        'permit_number'  => [
            "label" => 'Permit Number',
            "rules" => 'permit_empty'
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
            ->select("inventory_purchase.*,
            GROUP_CONCAT( DISTINCT inventory_purchase.first_name , SPACE(2), inventory_purchase.last_name ) as vendor_name,
            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.trim ) as trim,
            GROUP_CONCAT( DISTINCT inventory.stock_no ) as stock_no,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin
              ")
            ->join('inventory', 'inventory_purchase.inventory_id = inventory.inventory_id', 'left')
            ->groupBy('inventory_purchase.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
