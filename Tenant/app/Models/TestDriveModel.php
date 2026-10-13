<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class TestDriveModel extends ExactSearchableModel
{
    protected $table = 'test_drives';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'status',
        'first_name',
        'last_name',
        'phone',
        'email',
        'stock_no',
        'vin',
        'sales_rep_name'
    ];

    protected $allowedFields = [
        'test_drives_customers_id',
        'inventory_id',
        'sales_rep_id',
        'status',
        'start_time',
        'end_time',
        'odo_start',
        'odo_end',
        'number_plate',
        'session_token',
        'qr_code_url',
        'customer_signature_url',
        'insurance_url',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',
        'search_keyword',
        'module',
        'module_id'
    ];

    protected $useSoftDeletes = true;
    protected $returnType = 'Tenant\app\Entities\TestDrive';
    protected $useTimestamps = true;

    protected $validationRules = [
        'test_drives_customers_id' => [
            'label' => 'Customer',
            'rules' => 'required|is_natural_no_zero'
        ],
        'inventory_id' => [
            'label' => 'Vehicle',
//            'rules' => 'required|is_natural_no_zero'
            'rules' => 'required'
        ],
        'sales_rep_id' => [
            'label' => 'Sales Representative',
            'rules' => 'required|is_natural_no_zero'
        ],
        'status' => [
            'label' => 'Status',
            'rules' => 'required|in_list[pending,in_progress,completed,cancelled]'
        ],
        'odo_start' => [
            'label' => 'Starting Odometer',
            'rules' => 'required|is_natural'
        ],
        'number_plate' => [
            'label' => 'Number Plate',
            'rules' => 'required|alpha_numeric'
        ]
    ];

    protected $validationMessages = [
        'test_drives_customers_id' => [
            'required' => 'Customer is required',
            'is_natural_no_zero' => 'Invalid customer selected'
        ],
        'inventory_id' => [
            'required' => 'Vehicle is required',
//            'is_natural_no_zero' => 'Invalid vehicle selected'
        ],
        'sales_rep_id' => [
            'required' => 'Sales representative is required',
            'is_natural_no_zero' => 'Invalid sales person selected'
        ],
        'status' => [
            'in_list' => 'Invalid status value'
        ],
        'number_plate' => [
            'alpha_numeric' => 'Number plate can only contain letters and numbers'
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
                "test_drives.*,
                test_drives_customers.first_name,
                test_drives_customers.last_name,
                test_drives_customers.phone,
                test_drives_customers.email,
                test_drives_customers.street_address,
                test_drives_customers.city,
                test_drives_customers.province,
                CASE 
                    WHEN TRIM(COALESCE(test_drives.notes, '')) = '' THEN 'No'
                    ELSE 'Yes'
                END AS  issue,
                concat(inventory.year, ' ', inventory.make, ' ', inventory.model, ' ', inventory.trim) as vehicle,
                inventory.year,
                inventory.make,
                inventory.model,
                inventory.trim,
                inventory.stock_no as stock_no,
                inventory.vin as vin
             "
            )
            ->join('test_drives_customers', 'test_drives_customers.id = test_drives.test_drives_customers_id', 'left')
            ->join('inventory', 'inventory.stock_no = test_drives.inventory_id COLLATE utf8mb4_general_ci', 'left',false)
            //->groupBy('test_drives.id')
            ->limit($limit, $offset)
            ->get();

//        echo (string) db_connect()->getLastQuery();
//        die();

        $row = $row->getResult($this->tempReturnType);
        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);
        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

}