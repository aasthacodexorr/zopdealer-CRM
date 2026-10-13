<?php

namespace Tenant\app\Libraries\Captcha\Solver;

class TwoCaptcha
{

    private $api_key = '';

    public function __construct()
    {
        $this->api_key = getenv('captcha.solver.api.key');
    }

    /**
     * send
     *
     * @param  String $image accepts base64 image
     * @return array
     */
    public function send(String $image): array
    {
        try {
            $ch = curl_init();

            $body = [];
            $body['clientKey'] = $this->api_key;
            $body['task'] = [];
            $body['task']['type'] = 'ImageToTextTask';
            $body['task']['body'] = $image;
            $body['task']['case'] = true;
            $body['task']['comment'] = 'Case Sensitive';

            curl_setopt($ch, CURLOPT_URL, 'https://api.2captcha.com/createTask');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

            $headers = [];
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $response = json_decode($result, true);

            if (!empty($response['taskId'])) {
                $response['success'] = 'Captcha request sent.';
                $response['id'] = $response['taskId'];
            } else if (!empty($response['errorCode'])) {
                $response['errors'] = $response['errorCode'];
            } else {
                $response['errors'] = 'Some error occurred.';
            }

            return $response;
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }
    }

    /**
     * getResult
     *
     * @param  mixed $id
     * @return array
     */
    public function getResult($id): array
    {
        try {
            $ch = curl_init();

            $body = [];
            $body['clientKey'] = $this->api_key;
            $body['taskId'] = $id;


            curl_setopt($ch, CURLOPT_URL, 'https://api.2captcha.com/getTaskResult');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

            $headers = [];
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $response = json_decode($result, true);

            if (!empty($response['solution'])) {
                $response['success'] = 'Captcha resolved.';
            } else if (!empty($response['errorCode'])) {
                $response['errors'] = $response['errorCode'];
            } else {
                $response['errors'] = 'Some error occurred.';
            }

            return $response;
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }
    }
}
