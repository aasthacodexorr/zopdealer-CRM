<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

/**
 * SocialConnectModel This method is to log.
 */
class SocialConnectModel extends Model
{
    protected $table      = 'social_connect';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'stock_no','facebook_status','instagram_status','google_status','created_at', 'updated_at'
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