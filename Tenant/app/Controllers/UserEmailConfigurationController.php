<?php

namespace Tenant\app\Controllers;

use App\Libraries\Mailbox\IMAP;
use Tenant\app\Repositories\VendorRepository;
use App\Policies\AuthPolicy;
use Tenant\app\Repositories\UserEmailConfigurationRepository;

class UserEmailConfigurationController extends \App\Controllers\BaseController
{
    /**
     * repository This repository controls the CRUD related to saving the User Email Configurations
     * @var UserEmailConfigurationRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new UserEmailConfigurationRepository();
    }

    public function create()
    {
        $data = $this->request->getPost();
        $valid = false;
        $host = isset($data['incoming_hostname']) ? $data['incoming_hostname'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';
        $port = isset($data['incoming_port']) ? $data['incoming_port'] : '993';
        $secure = isset($data['incoming_secure']) ? $data['incoming_secure'] : 'ssl';

        $imap = new IMAP($host, $email, $password, $port, $secure, null);
        $valid = $imap->testConnection();

        if (service('authPolicy')->isTenant() && $valid) {
            $data['user_id'] = service('authPolicy')->id;
            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            if (!$valid) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'There was an error in connection.'])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        }
    }

    public function show()
    {
        if (service('authPolicy')->isTenant()) {
            $response = $this->repository->findByUserId(service('authPolicy')->id);
            if (isset($response)) {
                if (!empty($response)) {
                    unset($response['password']);
                }
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "User Email Connection not found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function delete($id)
    {

        if (service('authPolicy')->isTenant()) {

            try {
                $this->repository->delete($id);
                $this->response->setContentType('application/json')->setJSON(["success" => 'Email Configuration has been deleted.'])->send();
            } catch (\Exception $e) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['error' => 'There were some error in deleting the Email Configuration.'])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
