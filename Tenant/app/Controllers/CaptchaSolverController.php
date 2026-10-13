<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Captcha\Solver\TwoCaptcha;
use Tenant\app\Repositories\ConfigurationRepository;

class CaptchaSolverController extends \App\Controllers\BaseController
{

    private TwoCaptcha $captcha_solver;

    public function __construct()
    {
        $this->captcha_solver = new TwoCaptcha();
    }


    public function sendCaptcha()
    {

        $validated = $this->validate([
            'image' => [
                'label' => 'Image',
                'rules' => 'required',
            ],
            'token' => [
                'label' => 'Token',
                'rules' => 'required',
            ]
        ]);

        if ($validated) {
            $data = $this->request->getPost();
            $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

            if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
                $response = $this->captcha_solver->send($data['image']);

                if (isset($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }


    public function getResult()
    {

        $validated = $this->validate([
            'id' => [
                'label' => 'id',
                'rules' => 'required',
            ],
            'token' => [
                'label' => 'Token',
                'rules' => 'required',
            ]
        ]);

        if ($validated) {
            $data = $this->request->getPost();
            $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

            if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
                $response = $this->captcha_solver->getResult($data['id']);

                if (isset($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }
}
