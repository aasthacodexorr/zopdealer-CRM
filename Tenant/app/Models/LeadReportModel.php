<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LeadReportModel extends Model
{
    protected $table      = 'lead_responses';
    protected $primaryKey = 'id';

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\LeadResponse';
    protected $useTimestamps = true;

     /**
     * findAll Overidding findAll method and returning the objects with Images Array.
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
            ->select("
            lead_responses.id,
            lead_responses.first_name,
            lead_responses.last_name,
            lead_responses.phone,
            lead_responses.email,
            lead_responses.source,
            lead_responses.status,
            lead_responses.assignee,
            lead_responses.stock_no,
            lead_responses.created_at,
            lead_responses.updated_at,

            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin
              ")
            ->join('inventory', 'inventory.stock_no = lead_responses.stock_no', 'left')
            ->groupBy('lead_responses.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }

}