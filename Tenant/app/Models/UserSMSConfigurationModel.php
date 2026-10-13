<?php

namespace Tenant\app\Models;

use App\Helpers\Utility;
use CodeIgniter\Model;

class UserSMSConfigurationModel extends Model
{
    protected $table      = 'user_sms_configuration';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'phone',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'user_id' => [
            "label" => 'User Id',
            "rules" => 'required|is_unique[user_sms_configuration.user_id,id,{id}]'
        ],
        'phone' => [
            "label" => 'Phone',
            "rules" => 'required|is_natural|exact_length[10]'
        ]
    ];

}
