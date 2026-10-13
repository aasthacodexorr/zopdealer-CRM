<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class InventoryReportModel extends SearchableModel
{
    protected $table      = 'inventory';
    protected $primaryKey = 'inventory_id';

    protected $searchableColumns = [
        'year',
        'make',
        'model',
        'trim',
        'odometer',
        'price',
        'exterior_color',
        'interior_color',
        'transmission',
        'body_type',
        'drivetrain',
        'stock_no',
        'vin',
        'engine'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Inventory';
    protected $useTimestamps = true;

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
            ->select("inventory.inventory_id,
            inventory.visibility,
            inventory.year,
            inventory.make,
            inventory.model,
            inventory.trim,
            inventory.stock_date,
            inventory.stock_no,
            inventory.vin,
            inventory.price,
            special_price,
            ( SELECT IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) as selling_price,
            inventory.exterior_color,
            inventory.odometer,
            inventory.km_miles,
            inventory.feature_listing,
            inventory.transmission,
            inventory.body_type,
            inventory.status,
            inventory.vehicle_type,
            inventory.location,
            inventory.created_at,
            inventory.updated_at,
            inventory.days_in_stock,
            GROUP_CONCAT( DISTINCT inventory_purchase.first_name , SPACE(2), inventory_purchase.last_name ) as vendor_name,
            GROUP_CONCAT( DISTINCT inventory_purchase.phone ) as vendor_phone,
            GROUP_CONCAT( DISTINCT inventory_purchase.mobile ) as vendor_mobile,
            GROUP_CONCAT( DISTINCT inventory_purchase.email ) as vendor_email,
            GROUP_CONCAT( DISTINCT inventory_purchase.plate_number ) as vendor_plate_number,
            GROUP_CONCAT( DISTINCT inventory_purchase.company_name ) as vendor_company_name,

            GROUP_CONCAT( DISTINCT inventory_purchase.apt ) as vendor_apt,
            GROUP_CONCAT( DISTINCT inventory_purchase.street_address ) as vendor_street_address,
            GROUP_CONCAT( DISTINCT inventory_purchase.city ) as vendor_city,
            GROUP_CONCAT( DISTINCT inventory_purchase.province ) as vendor_province,
            GROUP_CONCAT( DISTINCT inventory_purchase.postal_code ) as vendor_postal_code,
            GROUP_CONCAT( DISTINCT inventory_purchase.country ) as vendor_country,
            GROUP_CONCAT( DISTINCT inventory_purchase.lien_holder_name ) as lien_holder_name,

            GROUP_CONCAT( DISTINCT inventory_purchase.purchase_date ) as vendor_purchase_date,

            GROUP_CONCAT( DISTINCT inventory_purchase.has_been_paid_for ) as inventory_paid_status,
            GROUP_CONCAT( DISTINCT inventory_purchase.has_title_been_received ) as has_title_been_received,
            GROUP_CONCAT( DISTINCT inventory_purchase.has_title_been_transferred ) as has_title_been_transferred,
            GROUP_CONCAT( DISTINCT inventory_purchase.has_lien ) as has_lien,
            GROUP_CONCAT( DISTINCT inventory_purchase.lien_has_been_paid ) as lien_has_been_paid,

            GROUP_CONCAT( DISTINCT inventory_purchase.payment_date ) as     inventory_paid_date,
            GROUP_CONCAT( DISTINCT inventory_purchase.title_received_date ) as title_received_date,
            GROUP_CONCAT( DISTINCT inventory_purchase.title_transfer_date ) as title_transfer_date,
            GROUP_CONCAT( DISTINCT inventory_purchase.lien_paid_date ) as lien_paid_date,

            GROUP_CONCAT( DISTINCT inventory_purchase.key_count ) as inventory_key_count,

            COALESCE(inventory_purchase.price,0) as vendor_purchase_price,
            COALESCE(inventory_purchase.discount,0) as vendor_purchase_discount, 
            MIN(COALESCE(inventory_purchase.tax_amount,0)) as vendor_purchase_primary_tax,
            MIN(COALESCE(inventory_purchase.tax_amount_secondary,0)) as vendor_purchase_secondary_tax,
            COALESCE(inventory_purchase.tax_amount_total,0) as vendor_purchase_total_tax,
            MIN(COALESCE(inventory_purchase.lien_amount,0)) as lien_amount,

            COALESCE((SELECT SUM((inventory_costs.price * inventory_costs.quantity)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost, 
            COALESCE((SELECT SUM((inventory_costs.discount)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost_discount, 
            COALESCE((SELECT SUM((inventory_costs.tax_amount)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost_primary_tax,
            COALESCE((SELECT SUM((inventory_costs.tax_amount_secondary)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost_secondary_tax,  
            COALESCE((SELECT SUM((inventory_costs.tax_amount_total)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost_total_tax,  
            COALESCE((SELECT SUM((inventory_costs.sub_total)) FROM inventory_costs WHERE inventory_costs.inventory_id = inventory.inventory_id ), 0) as additional_cost_total,  

            COALESCE((SELECT  (vendor_purchase_price - vendor_purchase_discount + additional_cost - additional_cost_discount)),0) as net_purchase_total,

            GROUP_CONCAT( DISTINCT customers.first_name , SPACE(2), customers.last_name ) as customer_name,
            GROUP_CONCAT( DISTINCT customers.phone ) as customer_phone,
            GROUP_CONCAT( DISTINCT customers.mobile ) as customer_mobile,
            GROUP_CONCAT( DISTINCT customers.email ) as customer_email,
            GROUP_CONCAT( DISTINCT customers.company_name ) as customer_company_name,
            
            GROUP_CONCAT( DISTINCT customers.apt ) as customer_apt,
            GROUP_CONCAT( DISTINCT customers.street_address ) as customer_street_address,
            GROUP_CONCAT( DISTINCT customers.city ) as customer_city,
            GROUP_CONCAT( DISTINCT customers.province ) as customer_province,
            GROUP_CONCAT( DISTINCT customers.postal_code ) as customer_postal_code,
            GROUP_CONCAT( DISTINCT customers.country ) as customer_country,

            GROUP_CONCAT( DISTINCT deals.delivery_date_time ) as delivery_date_time
              ")
            ->join('inventory_purchase', 'inventory_purchase.inventory_id = inventory.inventory_id', 'left')
            ->join('inventory_costs', 'inventory_costs.inventory_id = inventory.inventory_id', 'left')
            ->join('deal_inventory_pivot_table', 'inventory.stock_no = deal_inventory_pivot_table.stock_no', 'left')
            ->join('deals', 'deal_inventory_pivot_table.deal_id = deals.id AND deals.status ="Delivered"', 'left')
            ->join('deal_customer_pivot_table', 'deal_customer_pivot_table.deal_id = deals.id AND deal_customer_pivot_table.customer_type="primary_customer"', 'left')
            ->join('customers', 'customers.id = deal_customer_pivot_table.customer_id', 'left')
            ->groupBy('inventory.inventory_id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
