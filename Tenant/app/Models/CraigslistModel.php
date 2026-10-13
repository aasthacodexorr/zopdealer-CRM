<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

/**
 * CraigslistModel This method is to log Craigslist Sync.
 */
class CraigslistModel extends Model
{
    protected $table      = 'craigslist';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'stock_no','post_id','status','url', 'created_at', 'updated_at', 'deleted_at', 'location', 'current_modification_hash'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'stock_no'  => [
            "label" => 'Stock No',
            "rules" => 'required'
        ]
    ];

}