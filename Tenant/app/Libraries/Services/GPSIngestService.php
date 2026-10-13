<?php

namespace Tenant\app\Libraries\Services;

use Tenant\app\Models\TestDriveGpsPointModel;
use Tenant\app\Models\TestDriveEventModel;
use Tenant\app\Repositories\TestDriveRepository;

class GPSIngestService
{
    protected $gpsPointModel;
    protected $testDriveRepository;
    protected $eventModel;

    public function __construct()
    {
        $this->gpsPointModel = new TestDriveGpsPointModel();
        $this->testDriveRepository = new TestDriveRepository();
        $this->eventModel = new TestDriveEventModel();
    }

    /**
     * Ingest GPS points from mobile app
     */
    public function ingestGPSPoints(string $sessionToken, array $points): array
    {
        // Validate session token
        $testDrive = $this->testDriveRepository->getBySessionToken($sessionToken);
        
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['session' => 'Invalid session token']];
        }

        if ($testDrive->status !== 'in_progress') {
            return ['success' => false, 'errors' => ['status' => 'Test drive is not in progress']];
        }

        if (empty($points)) {
            return ['success' => false, 'errors' => ['points' => 'No GPS points provided']];
        }

        // Prepare points for batch insert
        $insertData = [];
        foreach ($points as $point) {
            if (!isset($point['latitude']) || !isset($point['longitude'])) {
                continue;
            }

            $insertData[] = [
                'test_drive_id' => $testDrive->id,
                'latitude' => $point['latitude'],
                'longitude' => $point['longitude'],
                'accuracy' => $point['accuracy'] ?? null,
                'altitude' => $point['altitude'] ?? null,
                'speed' => $point['speed'] ?? null,
                'heading' => $point['heading'] ?? null,
                'recorded_at' => $point['timestamp'] ?? date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        if (empty($insertData)) {
            return ['success' => false, 'errors' => ['points' => 'No valid GPS points provided']];
        }

        // Batch insert GPS points
        if ($this->gpsPointModel->bulkInsertPoints($insertData)) {
            // Log event (only log every 10 points to avoid spam)
            if (count($insertData) % 10 === 0) {
                $this->eventModel->logEvent($testDrive->id, 'gps_tracked', [
                    'points_count' => count($insertData)
                ]);
            }

            return [
                'success' => true,
                'points_saved' => count($insertData),
                'message' => 'GPS points saved successfully'
            ];
        }

        return ['success' => false, 'errors' => ['database' => 'Failed to save GPS points']];
    }

    /**
     * Ingest single GPS point
     */
    public function ingestSinglePoint(string $sessionToken, array $point): array
    {
        return $this->ingestGPSPoints($sessionToken, [$point]);
    }

    /**
     * Get GPS route for a test drive
     */
    public function getRoute(int $testDriveId): array
    {
        $testDrive = $this->testDriveRepository->find($testDriveId);
        
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['test_drive' => 'Test drive not found']];
        }

        $route = $this->gpsPointModel->getRoute($testDriveId);

        return [
            'success' => true,
            'route' => $route,
            'total_points' => count($route)
        ];
    }

    /**
     * Calculate distance traveled based on GPS points
     */
    public function calculateDistance(int $testDriveId): float
    {
        $points = $this->gpsPointModel->getByTestDriveId($testDriveId);
        
        if (count($points) < 2) {
            return 0.0;
        }

        $totalDistance = 0.0;
        
        for ($i = 1; $i < count($points); $i++) {
            $totalDistance += $this->haversineDistance(
                $points[$i - 1]->latitude,
                $points[$i - 1]->longitude,
                $points[$i]->latitude,
                $points[$i]->longitude
            );
        }

        return round($totalDistance, 2);
    }

    /**
     * Calculate distance between two GPS coordinates using Haversine formula
     */
    protected function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

