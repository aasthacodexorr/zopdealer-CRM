<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class DealReplicatorModel extends Model
{
    protected $table      = 'deal_replicators';
    protected $primaryKey = 'id';
    

    protected $allowedFields = [
        'title', 'description', 'retail_or_wholesale_flag', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\Deal';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'title'  => [
            "label" => 'Title',
            "rules" => 'required|is_unique[deal_replicators.title,id,{id}]'
        ],
        'description'  => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'retail_or_wholesale_flag'  => [
            "label" => 'Retail or Wholesale Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        
    ];

}
