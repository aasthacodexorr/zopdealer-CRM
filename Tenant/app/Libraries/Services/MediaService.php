<?php

namespace Tenant\app\Libraries\Services;

use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Models\TestDrivePhotoModel;
use Tenant\app\Models\TestDriveDocumentModel;
use Tenant\app\Models\TestDriveModel;
use Tenant\app\Models\TestDriveEventModel;

class MediaService
{
    protected $photoModel;
    protected $documentModel;
    protected $testDriveModel;
    protected $eventModel;

    public function __construct()
    {
        $this->photoModel = new TestDrivePhotoModel();
        $this->documentModel = new TestDriveDocumentModel();
        $this->testDriveModel = new TestDriveModel();
        $this->eventModel = new TestDriveEventModel();
    }

    /**
     * Upload photo for test drive
     */
    public function uploadPhoto(int $testDriveId, $file, string $photoType, ?string $caption = null): array
    {
        // Validate test drive exists
        $testDrive = $this->testDriveModel->find($testDriveId);
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['test_drive' => 'Test drive not found']];
        }

        // Validate photo type
        if (!in_array($photoType, ['pre', 'post'])) {
            return ['success' => false, 'errors' => ['photo_type' => 'Invalid photo type. Must be pre or post']];
        }

        // Upload file to cloud storage
        $fileName = $file->getRandomName();
        $storagePath = ROOT_IMAGE . '/' . md5(db_connect()->database) . '/test-drives/' . $testDriveId . '/' . $fileName;
        
        try {
            CloudStorageFactory::getInstance()->save($file, $storagePath);
            $photoUrl = '/' . $storagePath;

            // Save photo record
            $photoData = [
                'test_drive_id' => $testDriveId,
                'photo_type' => $photoType,
                'photo_url' => $photoUrl,
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'caption' => $caption
            ];

            if ($this->photoModel->save($photoData)) {
                $photoId = $this->photoModel->getInsertID();

                // Log event
                $this->eventModel->logEvent($testDriveId, 'photo_uploaded', [
                    'photo_id' => $photoId,
                    'photo_type' => $photoType
                ]);

                return [
                    'success' => true,
                    'photo_id' => $photoId,
                    'photo_url' => $photoUrl,
                    'message' => 'Photo uploaded successfully'
                ];
            }

            return ['success' => false, 'errors' => $this->photoModel->errors()];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['upload' => 'Failed to upload photo: ' . $e->getMessage()]];
        }
    }

    /**
     * Upload document for test drive
     */
    public function uploadDocument(int $testDriveId, $file, string $documentType): array
    {
        // Validate test drive exists
        $testDrive = $this->testDriveModel->find($testDriveId);
        if (!$testDrive) {
            return ['success' => false, 'errors' => ['test_drive' => 'Test drive not found']];
        }

        // Validate document type
        if (!in_array($documentType, ['driver_license', 'signature', 'insurance','gps'])) {
            return ['success' => false, 'errors' => ['document_type' => 'Invalid document type']];
        }

        // Upload file to cloud storage
        $fileName = $file->getRandomName();
        $storagePath = ROOT_FILE . '/' . md5(db_connect()->database) . '/test-drives/' . $testDriveId . '/' . $fileName;
        
        try {
            CloudStorageFactory::getInstance()->save($file, $storagePath);
            $documentUrl = '/' . $storagePath;

            // Save document record
            $documentData = [
                'test_drive_id' => $testDriveId,
                'document_type' => $documentType,
                'document_url' => $documentUrl,
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ];

            if ($this->documentModel->save($documentData)) {
                $documentId = $this->documentModel->getInsertID();

                // Update test drive with document URL
                if ($documentType === 'insurance') {
                    $this->testDriveModel->save([
                        'id' => $testDriveId,
                        'insurance_url' => $documentUrl
                    ]);
                } elseif ($documentType === 'signature') {
                    $this->testDriveModel->save([
                        'id' => $testDriveId,
                        'customer_signature_url' => $documentUrl
                    ]);
                }

                // Log event
                $this->eventModel->logEvent($testDriveId, 'document_uploaded', [
                    'document_id' => $documentId,
                    'document_type' => $documentType
                ]);

                return [
                    'success' => true,
                    'document_id' => $documentId,
                    'document_url' => $documentUrl,
                    'message' => 'Document uploaded successfully'
                ];
            }

            return ['success' => false, 'errors' => $this->documentModel->errors()];
        } catch (\Exception $e) {
            return ['success' => false, 'errors' => ['upload' => 'Failed to upload document: ' . $e->getMessage()]];
        }
    }

    /**
     * Get all photos for a test drive
     */
    public function getPhotos(int $testDriveId, ?string $type = null): array
    {
        if ($type) {
            $photos = $this->photoModel->getByType($testDriveId, $type);
        } else {
            $photos = $this->photoModel->getByTestDriveId($testDriveId);
        }

        return [
            'success' => true,
            'photos' => $photos
        ];
    }

    /**
     * Get all documents for a test drive
     */
    public function getDocuments(int $testDriveId, ?string $type = null): array
    {
        if ($type) {
            $documents = $this->documentModel->getByType($testDriveId, $type);
        } else {
            $documents = $this->documentModel->getByTestDriveId($testDriveId);
        }

        return [
            'success' => true,
            'documents' => $documents
        ];
    }
}

