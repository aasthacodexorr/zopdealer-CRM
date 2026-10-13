<?php

namespace Tenant\app\Models;

use App\Models\ExactSearchableModel;

class DigitalVehicleInspectionModel extends ExactSearchableModel
{
    protected $table      = 'digital_vehicle_inspections';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'title',
        'stock_no'
    ];

    protected $allowedFields = [
        'title',
        'description',
        'odometer',
        'require_odometer',
        'issues_count',
        'stock_no',
        'status',
        'assignee',
        'created_at',
        'updated_at',
        'deleted_at',
        'search_keyword'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    public function findAll(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder
            ->select("
            digital_vehicle_inspections.*,

            GROUP_CONCAT( DISTINCT inventory.year ) as year,
            GROUP_CONCAT( DISTINCT inventory.make ) as make,
            GROUP_CONCAT( DISTINCT inventory.model ) as model,
            GROUP_CONCAT( DISTINCT inventory.vin ) as vin
              ")
            ->join('inventory', 'inventory.stock_no = digital_vehicle_inspections.stock_no', 'left')
            ->groupBy('digital_vehicle_inspections.id')
            ->limit($limit, $offset)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }

    protected $validationRules    = [
        'title'                          => [
            "label" => 'Title',
            "rules" => 'required|min_length[2]'
        ],
        'description'                          => [
            "label" => 'Description',
            "rules" => 'permit_empty'
        ],
        'require_odometer'                        => [
            "label" => 'Odometer Required',
            "rules" => 'permit_empty|is_natural'
        ],
        'odometer'                        => [
            "label" => 'Odometer',
            "rules" => 'permit_empty|is_natural'
        ],
        'stock_no'                        => [
            "label" => 'Stock Number',
            "rules" => 'required'
        ],
        'status'                        => [
            "label" => 'Status',
            "rules" => 'required'
        ],
        'assignee'                        => [
            "label" => 'Assignee',
            "rules" => 'required'
        ]
    ];
}
