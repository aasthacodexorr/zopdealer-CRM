<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class VehicleDescriptionTemplateModel extends Model
{
    protected $table      = 'vehicle_description_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'description',
        'favourite',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
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
        'title'                          => [
            "label" => 'Title',
            "rules" => 'required|is_unique[vehicle_description_templates.title,id,{id}]'
        ]
    ];
}
