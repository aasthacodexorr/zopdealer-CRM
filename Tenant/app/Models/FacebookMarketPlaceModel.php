<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

/**
 * FacebookMarketPlaceModel This method is to log FacebookMarketPlace Sync.
 */
class FacebookMarketPlaceModel extends Model
{
    protected $table      = 'facebook_marketplace';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'stock_no','post_id','status', 'created_at', 'updated_at', 'deleted_at', 'current_modification_hash'
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