<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table      = 'images';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'image_url',
        'type',
        'index',
        'active',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\Image';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'image_url'                        => [
            "label" => 'Image Url',
            "rules" => 'required'
        ],
        'type'                        => [
            "label" => 'Type',
            "rules" => 'required'
        ],

    ];
}
