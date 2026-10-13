<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class DealCustomerInventoryModel extends Model
{
    protected $table      = 'deals';
    protected $primaryKey = 'id';

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Deal';
    protected $useTimestamps = true;

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
            ->select("deals.* , 

            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin,
            GROUP_CONCAT( DISTINCT inventory.stock_no ) as stock_no,
            GROUP_CONCAT( DISTINCT inventory.odometer, SPACE(2), inventory.km_miles ) as odometer,
            GROUP_CONCAT( DISTINCT inventory.exterior_color ) as exterior_color,
            GROUP_CONCAT( DISTINCT inventory.interior_color ) as interior_color,

            GROUP_CONCAT( DISTINCT customers.first_name ) as customer_first_name,
            GROUP_CONCAT( DISTINCT customers.last_name ) as customer_last_name,
            GROUP_CONCAT( DISTINCT customers.phone ) as customer_phone,
            GROUP_CONCAT( DISTINCT customers.mobile ) as customer_mobile,
            GROUP_CONCAT( DISTINCT customers.email ) as customer_email,
            GROUP_CONCAT( DISTINCT customers.company_name ) as customer_company_name,
            
            GROUP_CONCAT( DISTINCT customers.apt ) as customer_apt,
            GROUP_CONCAT( DISTINCT customers.street_address ) as customer_street_address,
            GROUP_CONCAT( DISTINCT customers.city ) as customer_city,
            GROUP_CONCAT( DISTINCT customers.province ) as customer_province,
            GROUP_CONCAT( DISTINCT customers.postal_code ) as customer_postal_code,
            GROUP_CONCAT( DISTINCT customers.country ) as customer_country
            
            ", false
             )
            ->from("deals as d")
            ->join('deal_customer_pivot_table', 'deal_customer_pivot_table.deal_id = deals.id AND deal_customer_pivot_table.customer_type="primary_customer"' ,'inner')
            ->join('customers', 'customers.id = deal_customer_pivot_table.customer_id', 'inner')
            ->join('deal_inventory_pivot_table', 'deal_inventory_pivot_table.deal_id = deals.id' ,'inner')
            ->join('inventory', 'inventory.stock_no = deal_inventory_pivot_table.stock_no', 'inner')
            ->groupBy('deals.id')
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