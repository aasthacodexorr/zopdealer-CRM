<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class VoucherTemplateModel extends Model
{
    protected $table      = 'voucher_templates';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'description', 'disclosure', 'expiry_date_type',
        'expiry_date', 'expiry_date_interval', 'expiry_date_interval_unit',
        'template', 'created_at', 'updated_at', 'deleted_at', 'value'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\VoucherTemplate';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title' => [
            "label" => 'Title',
            "rules" => 'required|min_length[2]|is_unique[voucher_templates.title,id,{id}]'
        ],
        'expiry_date_type'  => [
            "label" => 'Expiry Date Type',
            "rules" => 'required'
        ],
        'expiry_date_interval'  => [
            "label" => 'Interval',
            "rules" => 'permit_empty|is_natural'
        ],
        'template'  => [
            "label" => 'Template',
            "rules" => 'required'
        ],
        'value' => [
            "label" => 'Value',
            "rules" => 'permit_empty|decimal'
        ]

    ];
}
