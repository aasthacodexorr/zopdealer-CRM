<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Services\MediaService;

class TestDriveMediaController extends \App\Controllers\BaseController
{
    protected $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService();
    }

    /**
     * Upload photo for test drive
     */
    public function uploadPhoto($testDriveId)
    {
        $validated = $this->validate([
            'photo' => [
                'uploaded[photo]',
                'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[photo,32000]',
            ],
            'photo_type' => [
                'label' => 'Photo Type',
                'rules' => 'required|in_list[pre,post]'
            ]
        ]);

        if (!$validated) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])->send();
            return;
        }

        $file = $this->request->getFile('photo');
        $photoType = $this->request->getPost('photo_type');
        $caption = $this->request->getPost('caption');

        $response = $this->mediaService->uploadPhoto($testDriveId, $file, $photoType, $caption);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Upload document for test drive
     */
    public function uploadDocument($testDriveId)
    {
        $validated = $this->validate([
            'document' => [
                'uploaded[document]',
                'mime_in[document,image/jpg,image/jpeg,image/png]',
                'max_size[document,32000]',
            ],
            'document_type' => [
                'label' => 'Document Type',
                'rules' => 'required|in_list[driver_license,signature,insurance,gps]'
            ]
        ]);

        if (!$validated) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])->send();
            return;
        }

        $file = $this->request->getFile('document');
        $documentType = $this->request->getPost('document_type');

        $response = $this->mediaService->uploadDocument($testDriveId, $file, $documentType);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }

    /**
     * Get photos for a test drive
     */
    public function getPhotos($testDriveId)
    {
        $type = $this->request->getGet('type');
        $response = $this->mediaService->getPhotos($testDriveId, $type);

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * Get documents for a test drive
     */
    public function getDocuments($testDriveId)
    {
        $type = $this->request->getGet('type');
        $response = $this->mediaService->getDocuments($testDriveId, $type);

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * Upload signature (base64 encoded image)
     */
    public function uploadSignature($testDriveId)
    {
        $signatureData = $this->request->getPost('signature_data');

        if (empty($signatureData)) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['signature' => 'Signature data is required']])->send();
            return;
        }

        // Decode base64 image
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));

        if ($imageData === false) {
            $this->response->setContentType('application/json')->setStatusCode(400)
                ->setJSON(['success' => false, 'errors' => ['signature' => 'Invalid signature data']])->send();
            return;
        }

        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'signature_');
        file_put_contents($tempFile, $imageData);

        // Create file object
        $file = new \CodeIgniter\HTTP\Files\UploadedFile(
            $tempFile,
            'signature_' . time() . '.png',
            'image/png',
            filesize($tempFile),
            UPLOAD_ERR_OK
        );

        $response = $this->mediaService->uploadDocument($testDriveId, $file, 'signature');

        // Clean up temp file
        @unlink($tempFile);

        if ($response['success']) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode(400)->setJSON($response)->send();
        }
    }
}

