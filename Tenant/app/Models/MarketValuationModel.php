<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class MarketValuationModel extends ExactSearchableModel
{
    protected $table      = 'market_valuations';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'year',
        'make',
        'model',
        'trim',
        'vin',

    ];

    protected $allowedFields = [
        'year',
        'make',
        'model',
        'trim',
        'vin',
        'status',
        'payload',
        'created_at',
        'updated_at',
        'deleted_at',
        'search_keyword',
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\MarketValuation';
    protected $useTimestamps = true;
}
