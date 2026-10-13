<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class ReviewFeedbackListModel extends ExactSearchableModel
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

    /**
     * findAll Overidding findAll method and returning the objects with Customer Name, Phone Number, Email
     *
     * @param  mixed $limit
     * @param  mixed $offset
     * @return void
     */
    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder
            ->select(
                "review_feedbacks.* ,
                GROUP_CONCAT( DISTINCT customers.first_name ) as first_name,
                GROUP_CONCAT( DISTINCT customers.last_name) as last_name,
                GROUP_CONCAT( DISTINCT customers.phone ) as phone,
                GROUP_CONCAT( DISTINCT customers.mobile ) as mobile,
                GROUP_CONCAT( DISTINCT customers.email ) as email,
             "
            )
            ->join('customers', 'customers.id = review_feedbacks.customer_id', 'left')
            ->groupBy('review_feedbacks.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }
}
