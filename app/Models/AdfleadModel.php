<?php

namespace App\Models;

use CodeIgniter\Model;

class AdfleadModel extends Model
{

    protected $table = 'adfdata';
    protected $primaryKey = 'id';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $allowedFields = [
        'xsender', 'xto', 'xsubject', 'xdate',
        'xdata', 'status','message'
    ];

    protected $useSoftDeletes = false;
    protected $returnType = 'App\Entities\Adflead';
    protected $useTimestamps = false;

    protected $validationRules = [
        'id' => [
            "label" => 'Adf Lead ID',
            "rules" => 'required'
        ]
    ];
}
