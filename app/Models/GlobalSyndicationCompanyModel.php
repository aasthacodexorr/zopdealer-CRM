<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * GlobalSyndicationCompanyModel This modelrepresents the schema of the Global Syndication Companies 
 * Table. 
 */
class GlobalSyndicationCompanyModel extends Model
{
    protected $table      = 'global_syndication_companies';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'label', 'slug', 'image_url',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\GlobalSyndicationCompany';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'label'                          => [
            "label" => 'Name',
            "rules" => 'required|alpha_numeric_space|min_length[2]'
        ],
        'slug'                          => [
            "label" => 'Slug',
            "rules" => 'required|alpha_dash|min_length[2]'
        ],
        'image_url' => [
            "label" => 'Image',
            "rules" => 'required'
        ],
    ];
}
