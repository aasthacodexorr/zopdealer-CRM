<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Repositories\AttachmentRepository;

class AttachmentController extends \App\Controllers\BaseController
{
    private $repository;

    public function __construct()
    {
        $this->repository = new AttachmentRepository();
    }

    public function create()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'attachment' => [
                'uploaded[attachment]',
                'mime_in[attachment,application/pdf,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[attachment,32000]',
            ],
            'module'  => [
                "label" => 'Module',
                "rules" => 'required|alpha_numeric_punct'
            ],
            'sub_module'  => [
                "label" => 'Sub Module',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ],
            'entity_id'  => [
                "label" => 'Entity Id',
                "rules" => 'required|is_natural'
            ],
        ]);

        if ($validated) {

            $file = $this->request->getFile('attachment');
            $name = $file->getRandomName();

            CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
            $data['url'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;

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

    public function deleteByUrl()
    {
        $data = $this->request->getPost();
        $validated = $this->validate([
            'url'  => [
                "label" => 'Url',
                "rules" => 'required'
            ],
        ]);

        if ($validated) {
            $this->repository->deleteByUrl($data['url']);
            $this->response->setContentType('application/json')->setJSON(['success' => $data['url'] .' has been deleted.' ])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }
}
