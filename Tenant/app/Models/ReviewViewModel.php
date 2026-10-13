<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class ReviewViewModel extends Model
{
    protected $table      = 'reviews_view';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'header', 'view', 'query', 'sort', 'default', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'header'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[reviews_view.header,id,{id}]'
        ],
    ];
}
