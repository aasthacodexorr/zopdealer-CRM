<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DisclosureTemplateModel extends Model
{
    protected $table      = 'complete_disclosure_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'description',
        'favourite',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'favourite'  => [
            "label" => 'Favourite',
            "rules" => 'permit_empty|is_natural'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'required'
        ],
    ];
}
