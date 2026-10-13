<?php

namespace App\Models;

use CodeIgniter\Model;

class DealerTrackConfigurationModel extends Model
{
    protected $table      = 'dealertrack_configuration';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'dealer_id',
        'dealer_confirm_id',
        'database',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $returnType    = 'CodeIgniter\Entity';

    protected $validationRules    = [
        'dealer_id'  => [
            "label" => 'Dealer ID',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'dealer_confirm_id'  => [
            "label" => 'Dealer Confimation Id',
            "rules" => 'permit_empty'
        ],
        'database'                        => [
            "label" => 'Database',
            "rules" => 'is_unique[dealertrack_configuration.database,id,{id}]'
        ]
    ];
}
