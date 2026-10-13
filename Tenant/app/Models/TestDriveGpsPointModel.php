<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TestDriveGpsPointModel extends Model
{
    protected $table = 'test_drive_gps_points';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'test_drive_id',
        'latitude',
        'longitude',
        'accuracy',
        'altitude',
        'speed',
        'heading',
        'recorded_at',
        'created_at',
        'updated_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules = [
        'test_drive_id' => [
            'label' => 'Test Drive',
            'rules' => 'required|is_natural_no_zero'
        ],
        'latitude' => [
            'label' => 'Latitude',
            'rules' => 'required|decimal'
        ],
        'longitude' => [
            'label' => 'Longitude',
            'rules' => 'required|decimal'
        ]
    ];

    /**
     * Get GPS points by test drive ID
     */
    public function getByTestDriveId(int $testDriveId)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->orderBy('recorded_at', 'ASC')
            ->findAll();
    }

    /**
     * Get GPS route as array of coordinates
     */
    public function getRoute(int $testDriveId): array
    {
        $points = $this->getByTestDriveId($testDriveId);
        $route = [];

        foreach ($points as $point) {
            $route[] = [
                'lat' => (float)$point->latitude,
                'lng' => (float)$point->longitude,
                'timestamp' => $point->recorded_at
            ];
        }

        return $route;
    }

    /**
     * Bulk insert GPS points
     */
    public function bulkInsertPoints(array $points): bool
    {
        if (empty($points)) {
            return false;
        }

        return parent::insertBatch($points) !== false;
    }

    /**
     * Get latest GPS point for a test drive - (UNUSED)
     */
    public function getLatestPoint(int $testDriveId)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->orderBy('recorded_at', 'DESC')
            ->first();
    }
}

