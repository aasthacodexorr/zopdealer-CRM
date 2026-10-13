<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class ReviewModel extends ExactSearchableModel
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'name',
        'source'
    ];

    protected $allowedFields = [
        'uuid',
        'profile_photo_url',
        'name',
        'visibility',
        'comment',
        'reply',
        'rating',
        'review_date_time',
        'review_reply_date_time',
        'source',
        'search_keyword',
        'created_at',
        'updated_at',
        'deleted_at',
        'featured',
        'location'
    ];

    protected $useSoftDeletes = false;
    protected $returnType = 'Tenant\app\Entities\Review';
    protected $useTimestamps = true;

    protected $validationRules = [
        'name' => [
            "label" => 'Name',
            "rules" => 'required'
        ],
        'rating' => [
            "label" => 'Rating',
            "rules" => 'required|decimal'
        ]
    ];
}