<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{

    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    protected $allowedFields = ['invoice_no', 'invoice_date', 'amount', 'status', 'subscription_no', 'data', 'invoice_fullid'];
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $useSoftDeletes = false;
    protected $returnType = 'Tenant\app\Entities\Syndication';
    protected $useTimestamps = false;
    protected $validationRules = [
        'invoice_no' => [
            "label" => 'Invoice#',
            "rules" => 'required'
        ],
        'amount' => [
            "label" => 'Invoice Amount',
            "rules" => 'required'
        ],
        'status' => [
            "label" => 'Status',
            "rules" => 'required'
        ],
        'subscription_no' => [
            "label" => 'Subscription No',
            "rules" => 'required'
        ],
        'invoice_date' => [
            "label" => 'Invoice Date',
            "rules" => 'required'
        ],
        'invoice_fullid' => [
            "label" => 'Invoice ID',
            "rules" => 'required'
        ]

    ];
}