<?php

namespace App\Models;

use CodeIgniter\Model;

class CarfaxOrderLogModel extends Model
{
    protected $table      = 'carfax_order_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'stock_no',
        'vin',
        'action',
        'response',
        'status',
        'user',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $returnType    = 'CodeIgniter\Entity';
}
