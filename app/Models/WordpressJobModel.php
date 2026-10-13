<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WordpressJobModel This model stores unique urls to be hit on the wordpress.
 */
class WordpressJobModel extends Model
{
    protected $table      = 'wordpress_jobs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'url',
        'schedule',
        'created_at',
        'updated_at',
        'deleted_at',
        'website_type'
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = true;

    protected $validationRules    = [
        'url' => [
            "label" => 'Url',
            "rules" => 'required|is_unique[wordpress_jobs.url]',
        ]
    ];
}
