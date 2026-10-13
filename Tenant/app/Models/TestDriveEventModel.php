<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TestDriveEventModel extends Model
{
    protected $table = 'test_drive_events';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'test_drive_id',
        'event_type',
        'event_data',
        'user_id',
        'ip_address',
        'user_agent',
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
        'event_type' => [
            'label' => 'Event Type',
            'rules' => 'required|in_list[created,started,completed,cancelled,updated,photo_uploaded,document_uploaded,gps_tracked]'
        ]
    ];

    /**
     * Get events by test drive ID
     */
    public function getByTestDriveId(int $testDriveId)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Log an event
     */
    public function logEvent(int $testDriveId, string $eventType, array $eventData = [], ?string $userId = null): bool
    {
        $request = \Config\Services::request();

        $data = [
            'test_drive_id' => $testDriveId,
            'event_type' => $eventType,
            'event_data' => json_encode($eventData),
            'user_id' => $userId,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString()
        ];

        return $this->insert($data) !== false;
    }

    /**
     * Get audit trail for a test drive
     */
    public function getAuditTrail(int $testDriveId): array
    {
        $events = $this->getByTestDriveId($testDriveId);
        $trail = [];

        foreach ($events as $event) {
            $trail[] = [
                'timestamp' => $event->created_at,
                'event' => $event->event_type,
                'data' => json_decode($event->event_data, true),
                'user' => $event->user_id,
                'ip' => $event->ip_address
            ];
        }

        return $trail;
    }
}

