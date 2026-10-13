<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class TestDrivePhotoModel extends Model
{
    protected $table = 'test_drive_photos';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'test_drive_id',
        'photo_type',
        'photo_url',
        'file_name',
        'file_size',
        'mime_type',
        'caption',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType = 'CodeIgniter\Entity';
    protected $useTimestamps = true;

    protected $validationRules = [
        'test_drive_id' => [
            'label' => 'Test Drive',
            'rules' => 'required|is_natural_no_zero'
        ],
        'photo_type' => [
            'label' => 'Photo Type',
            'rules' => 'required|in_list[pre,post]'
        ],
        'photo_url' => [
            'label' => 'Photo URL',
            'rules' => 'required'
        ]
    ];

    /**
     * Get photos by test drive ID
     */
    public function getByTestDriveId(int $testDriveId)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get photos by type (pre or post)
     */
    public function getByType(int $testDriveId, string $type)
    {
        return $this->where('test_drive_id', $testDriveId)
            ->where('photo_type', $type)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get pre-test drive photos - (UNUSED)
     */
    public function getPrePhotos(int $testDriveId)
    {
        return $this->getByType($testDriveId, 'pre');
    }

    /**
     * Get post-test drive photos - (UNUSED)
     */
    public function getPostPhotos(int $testDriveId)
    {
        return $this->getByType($testDriveId, 'post');
    }
}

