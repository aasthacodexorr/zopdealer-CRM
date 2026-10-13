<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Services\GPSIngestService;

class TestDriveGpsPointController extends \App\Controllers\BaseController
{
    protected $gpsService;

    public function __construct()
    {
        $this->gpsService = new GPSIngestService();
    }

    /**
     * Ingest GPS points from mobile app (batch)
     */
    public function ingest()
    {
        $sessionToken = $this->request->getPost('session_token') ?? $this->request->getHeaderLine('X-Session-Token');
        $points = $this->request->getPost('points');

        if (empty($sessionToken)) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['session' => 'Session token is required']])->send();
            return;
        }

        if (empty($points) || !is_array($points)) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['points' => 'GPS points array is required']])->send();
            return;
        }

        $response = $this->gpsService->ingestGPSPoints($sessionToken, $points);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Ingest single GPS point from mobile app
     */
    public function ingestSingle()
    {
        $sessionToken = $this->request->getPost('session_token') ?? $this->request->getHeaderLine('X-Session-Token');
        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $accuracy = $this->request->getPost('accuracy');
        $altitude = $this->request->getPost('altitude');
        $speed = $this->request->getPost('speed');
        $heading = $this->request->getPost('heading');
        $timestamp = $this->request->getPost('timestamp') ?? date('Y-m-d H:i:s');

        if (empty($sessionToken)) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['session' => 'Session token is required']])->send();
            return;
        }

        if (empty($latitude) || empty($longitude)) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['coordinates' => 'Latitude and longitude are required']])->send();
            return;
        }

        $point = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'accuracy' => $accuracy,
            'altitude' => $altitude,
            'speed' => $speed,
            'heading' => $heading,
            'timestamp' => $timestamp
        ];

        $response = $this->gpsService->ingestSinglePoint($sessionToken, $point);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Get GPS route for a test drive
     */
    public function getRoute($testDriveId)
    {
        $response = $this->gpsService->getRoute($testDriveId);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Get calculated distance for a test drive
     */
    public function getDistance($testDriveId)
    {
        $distance = $this->gpsService->calculateDistance($testDriveId);

        $response = [
            'success' => true,
            'distance_km' => $distance,
            'distance_miles' => round($distance * 0.621371, 2)
        ];

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }
}

