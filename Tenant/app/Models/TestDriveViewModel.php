<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TestDriveViewModel extends Model
{
    protected $table = 'test_drives_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header', 'view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType = 'Tenant\app\Entities\TestDriveView';
    protected $useTimestamps = true;

    protected $validationRules = [
        'header' => [
            "label" => 'Title',
            "rules" => 'required|is_unique[test_drives_view.header,id,{id}]'
        ],
    ];
    
}

