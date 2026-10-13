<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use App\Models\SearchableModel;
use CodeIgniter\Model;

class StripePaymentModel extends ExactSearchableModel
{
    protected $table      = 'payments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'date', 'transaction_id', 'event_id', 'amount', 'type',
        'receipt_email', 'status', 'receipt_url', 'receipt_number',
        'name', 'phone', 'email', 'mode', 'sub_status',
        'module', 'module_id',
        'created_at', 'updated_at', 'deleted_at', 'reason'
    ];

    protected $searchableColumns = [
        'name', 'phone', 'email', 'transaction_id', 'receipt_number', 'reason'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'module'  => [
            "label" => 'Module',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],

        'module_id'  => [
            "label" => 'Module Id',
            "rules" => 'permit_empty|is_natural'
        ],
    ];

    protected $beforeInsert = ['convertNumericData'];
    protected $beforeUpdate = ['convertNumericData'];

    protected function convertNumericData(array $data)
    {

        if (isset($data['data']['amount'])) {
            if (is_numeric($data['data']['amount'])) {
                $data['data']['amount'] = (int) $data['data']['amount'];
                $data['data']['amount'] /= 100;
            }
        }

        return $data;
    }
}
