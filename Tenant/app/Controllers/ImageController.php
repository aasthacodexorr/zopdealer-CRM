<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Libraries\TenantImage\TenantImageConstant;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\ImageRepository;

/**
 * ImageController : Controller for managing the images.
 */
class ImageController extends \App\Controllers\BaseController
{

    /**
     * repository
     *
     * @var ImageRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ImageRepository();
    }

    public function index()
    {

        $type = '';
        if ($this->request->getGet('type')) {
            $type = $this->request->getGet('type');
        }

        $response = $this->repository->switchImageType($type)->index();

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function create()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[file,32000]',
            ],
            'type' => [
                'label' => 'Type',
                'rules' => 'required'
            ]
        ]);

        $enable_zop_studio_config = (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('enable_zop_studio', ['value']);
        $is_zop_studio_enabled = !empty($enable_zop_studio_config['value']);

        if (!empty($data['type'])) {
            if ($data['type'] === TenantImageConstant::OVERLAY && $is_zop_studio_enabled) {
                $validated = false;
                $this->validator->setError('type', 'Zop Studio is enabled so overlay cannot be set.');
            }
        }


        if ($validated) {
            $file = $this->request->getFile('file');
            $name = $file->getRandomName();

            CloudStorageFactory::getInstance()->save($file, ROOT_IMAGE . '/' . md5(db_connect()->database) . '/' . $name);
            $data['image_url'] = "/" . ROOT_IMAGE . "/" . md5(db_connect()->database) . "/" . $name;
            $data['file_name'] = $name;
            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function delete($id)
    {
        $response = $this->repository->delete($id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function changeActivation($id)
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'active' => [
                'label' => 'Active',
                'rules' => 'required|is_natural',
            ]
        ]);

        if ($validated) {

            $response = $this->repository->changeActivation($id, $data['active']);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function getPlaceholder()
    {
        $response = $this->repository->getPlaceholder();
        $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
    }

    public function processUnsavedOverlayImages()
    {
        try {
            $response = $this->executeOverlayForUnprocessedImages();
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
            }
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => $ex->getMessage()])->send()->setHeader('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * executeOverlayForUnprocessedImages
     *
     * @return array
     */
    public function executeOverlayForUnprocessedImages(): array
    {
        $overlayImage = $this->repository->getActiveOverlayImages();
        if (count($overlayImage) > 0) {
            $database = db_connect()->database;
            $folder = 'image';
            $image_url = $overlayImage[0]['image_url'];
            $pos = strrpos($image_url, '/', -1);
            $file_name = substr($image_url, $pos + 1);
            $unprocessed_flag = "1";

            exec("php index.php imageQueue send $database $folder $file_name $unprocessed_flag");
            return ["success" => 'Request has been sent for processing overlay images.'];
        } else {
            return ["errors" => "No overlay image available."];
        }
    }
}
