<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealJacketDocumentModel extends Model
{
    protected $table      = 'deal_jacket_documents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'deal_id',
        'url',
        'section_type',
        'source',
        'type',
        'user',
        'created_at',
        'updated_at',
        'deleted_at',
        'has_tagging',
        'tagging'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'deal_id' => [
            "label" => 'Deal Id',
            "rules" => 'required'
        ],
        'url'  => [
            "label" => 'URL',
            "rules" => 'permit_empty'
        ],
        'section_type'  => [
            "label" => 'Section Type',
            "rules" => 'required'
        ],
        'source'  => [
            "label" => 'Source',
            "rules" => 'required'
        ],
        'type'  => [
            "label" => 'Type',
            "rules" => 'required'
        ],
        'user'  => [
            "label" => 'User',
            "rules" => 'permit_empty'
        ],
        'has_tagging'  => [
            "label" => 'Has Tagging',
            "rules" => 'permit_empty|is_natural'
        ],
        'tagging'  => [
            "label" => 'Tagging',
            "rules" => 'permit_empty|valid_json'
        ],
    ];
}
