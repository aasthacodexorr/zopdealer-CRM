<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class ReviewFeedbackModel extends ExactSearchableModel
{
    protected $table      = 'review_feedbacks';
    protected $primaryKey = 'id';

    protected $searchableColumns = [];

    protected $allowedFields = [
        'status', 'response', 'sent_counter', 'comment',
        'customer_id', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;
}
