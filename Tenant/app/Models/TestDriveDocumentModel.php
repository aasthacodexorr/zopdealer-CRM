<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TestDriveDocumentModel extends Model
{
    protected $table      = 'test_drive_documents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'test_drive_id',
        'document_type',
        'document_url',
        'file_name',
        'file_size',
        'mime_type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules = [
        'test_drive_id' => [
            'label' => 'Test Drive',
            'rules' => 'required|is_natural_no_zero'
        ],
        'document_type' => [
            'label' => 'Document Type',
            'rules' => 'required|in_list[driver_license,signature,insurance,gps]'
        ],
        'document_url' => [
            'label' => 'Document URL',
            'rules' => 'required'
        ]
    ];

    /**
     * Get documents by test drive ID
     */
    public function getByTestDriveId(int $testDriveId)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->where('deleted_at', null)
            ->findAll();
    }

    /**
     * Get documents by type
     */
    public function getByType(int $testDriveId, string $type)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->where('document_type', $type)
            ->where('deleted_at', null)
            ->findAll();
    }
}

