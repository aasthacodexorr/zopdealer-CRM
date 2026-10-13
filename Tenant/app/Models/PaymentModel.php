<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;
use Tenant\app\Repositories\ConfigurationRepository;

class PaymentModel extends ExactSearchableModel
{
    protected $table      = 'payments';
    protected $primaryKey = 'id';

    protected $beforeInsert = ['setReceiptNumber', 'createKeyWordString'];

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

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Payment';
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

    /**
     * setReceiptNumber This method sets receipt number from configuration if it is not set.
     *
     * @param  mixed $data
     * @return void
     */
    public function setReceiptNumber(array $data)
    {
        if (empty($data['data']['receipt_number'])) {
            $configuration_repository = new ConfigurationRepository();
            $prefix = $configuration_repository->findConfigurationColumnsByKey('payment_receipt_number_prefix', 'value')->value;
            $counter = $configuration_repository->findConfigurationColumnsByKey('payment_receipt_counter', 'value')->value;
            $data['data']['receipt_number'] = $prefix . str_pad($counter, 6, "0", STR_PAD_LEFT);
            $counter = intval($counter);
            $configuration_repository->setConfiguration(['payment_receipt_counter' =>  ++$counter]);
        }

        return $data;
    }
}
