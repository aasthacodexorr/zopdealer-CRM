<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Libraries\Google\Places;
use Tenant\app\Libraries\Captcha\SimpleCaptcha;
use Tenant\app\Libraries\OneTimeAuthentication\OTP;
use Tenant\app\Libraries\OCR\AzureDocumentAnalyzer;
use Tenant\app\Libraries\PDFParser\PDFParserFactory;


class ServiceController extends \App\Controllers\BaseController
{

    public function generateOTP()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'phone'  => [
                "label" => 'Phone Number',
                "rules" => 'required|is_natural|exact_length[10]'
            ],
        ]);

        if ($validated) {
            $generated_object = OTP::generate();

            $content =  $generated_object["OTP"] . " is your one time password (OTP) for phone verification.";

            $smsFactory = service('channelService')->getInstance("internal")['sms'];
            $smsFactory->setSid(getenv('twilio.sid'));
            $smsFactory->setAuth_token(getenv('twilio.auth_token'));
            $smsFactory->setSender(getenv('twilio.phone'));
            $smsFactory->setContent($content);
            $smsFactory->setRecipient('+1' . $data['phone']);
            $smsFactory->send();

            $this->response->setContentType('application/json')->setJSON(['hash' => $generated_object['hash']])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function generateCaptcha()
    {
        $generated_object = SimpleCaptcha::generate();
        $this->response->setContentType('application/json')->setJSON(['hash' => $generated_object['hash'], 'captcha' => $generated_object['captcha']])->send();
    }

    public function internalCrawlerZipCode(String $region)
    {
        $cache_key = "INTERNAL_ZIP_CODE_CRAWLER_INDEX_" . $region;
        $response = [];
        $zipcodes = [];

        if ($region === 'ontario') {
            $zipcodes = json_decode(file_get_contents(ROOTPATH . "/public/json/internal_ontario_zipcode.json"), true);
        }

        $response = $zipcodes;

        if (!$this->request->getGet('all')) {
            $index = 0;
            $limit = 20;

            if ($region === 'ontario') {
                $limit = 2;
            }

            $cache = \Config\Services::cache();
            if ($cache->isSupported()) {
                $current_index = $cache->get($cache_key);

                if (!empty($current_index)) {
                    $index = (int) $current_index;
                }

                $response = array_splice($zipcodes, $index, $limit);
                $index += $limit;

                if (empty($response)) {
                    $index = 0;
                    $response = array_splice($zipcodes, $index, $limit);
                }

                $cache->save($cache_key, $index);
            }
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }


    public function internalCrawlerCity(String $region)
    {
        $cache_key = "INTERNAL_CITY_CRAWLER_INDEX_" . $region;
        $response = [];
        $zipcodes = [];

        if ($region === 'alberta') {
            $zipcodes = json_decode(file_get_contents(ROOTPATH . "/public/json/internal_alberta_city.json"), true);
        } else if ($region === 'bc') {
            $zipcodes = json_decode(file_get_contents(ROOTPATH . "/public/json/internal_bc_city.json"), true);
        }

        $response = $zipcodes;

        if (!$this->request->getGet('all')) {
            $index = 0;
            $limit = 5;
            $cache = \Config\Services::cache();
            if ($cache->isSupported()) {
                $current_index = $cache->get($cache_key);

                if (!empty($current_index)) {
                    $index = (int) $current_index;
                }

                $response = array_splice($zipcodes, $index, $limit);
                $index += $limit;

                if (empty($response)) {
                    $index = 0;
                    $response = array_splice($zipcodes, $index, $limit);
                }

                $cache->save($cache_key, $index);
            }
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function internalCrawlerThirdParty(String $service)
    {
        $cache_key = "INTERNAL_THIRD_PARTY_CRAWLER_INDEX_" . $service;
        $response = [];
        $regions = [];

        if ($service === 'dealer-rater') {
            $regions = json_decode(file_get_contents(ROOTPATH . "/public/json/dealer-rater-state.json"), true);
        }

        $response = $regions;

        if (!$this->request->getGet('all')) {
            $index = 0;
            $limit = 1;
            $cache = \Config\Services::cache();
            if ($cache->isSupported()) {
                $current_index = $cache->get($cache_key);

                if (!empty($current_index)) {
                    $index = (int) $current_index;
                }

                $response = array_splice($regions, $index, $limit);
                $index += $limit;

                if (empty($response)) {
                    $index = 0;
                    $response = array_splice($regions, $index, $limit);
                }

                $cache->save($cache_key, $index);
            }
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    public function getLocationSuggestions()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'search' => [
                'label' => 'Location Address',
                'rules' => 'required',
            ]
        ]);

        if ($validated) {

            $response = Places::getSuggestions($data['search']);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function getALocation($place_id)
    {
        $response = Places::getLocation($place_id);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function parseIdentificationImage()
    {
        // log_message('error', 'OCR API HIT');
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpeg,image/png,application/pdf]',
                'max_size[file,15000]',
            ],
        ]);

        if (!$validated) {
            $this->response->setContentType('application/json')->setStatusCode('403')->setJSON($this->validator->getErrors())->send();
            return;
        }

        $file = $this->request->getFile('file');

        $analyzer = new AzureDocumentAnalyzer();
        $response = $analyzer->parse(['file' => $file]);

        if (isset($response['status']) && in_array($response['status'], ['success', 'pending'], true)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('403')->setJSON($response)->send();
        }
    }

    public function getParsedIdentificationImageResult($id)
    {
        $analyzer = new AzureDocumentAnalyzer();
        $response = $analyzer->getParsedResult($id);

        if (isset($response['status']) && $response['status'] === 'success') {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('403')->setJSON($response)->send();
        }
    }
}
