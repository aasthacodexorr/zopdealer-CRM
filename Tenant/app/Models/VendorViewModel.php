<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class VendorViewModel extends Model
{
    protected $table      = 'vendors_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header','view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\VendorView';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[vendors_view.header,id,{id}]'
        ],
    ];

}