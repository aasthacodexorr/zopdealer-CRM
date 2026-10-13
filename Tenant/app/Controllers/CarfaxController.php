<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Libraries\VinHistory\CarfaxV3;
use Tenant\app\Repositories\CarfaxOrderLogRepository;

class CarfaxController extends \App\Controllers\BaseController
{

    /**
     * carHistoryAPIInstance
     *
     * @var CarfaxV3
     */
    private $carHistoryAPIInstance;

    /**
     * @var ConfigurationRepository
     */
    private $configuration_controller;

    /**
     * carfax_order_log_repository
     *
     * @var CarfaxOrderLogRepository
     */
    private $carfax_order_log_repository;

    public function __construct()
    {
        $this->configuration_controller = new ConfigurationRepository();
        $this->carHistoryAPIInstance = new CarfaxV3();
        $this->carfax_order_log_repository = new CarfaxOrderLogRepository();
    }

    public function lookUp()
    {
        $data = $this->request->getPost();
        $validated = $this->validate([
            'vin'  => [
                "label" => 'Vin',
                "rules" => 'required'
            ],
            'stock_no'  => [
                "label" => 'Stock Number',
                "rules" => 'permit_empty'
            ],

        ]);

        if ($validated) {

            $carfax_account_token_as_array = $this->configuration_controller->findConfigurationColumnsByKeyAsArray('carfax_account_token', ['value']);
            if (!empty($carfax_account_token_as_array['value'])) {
                $response = $this->carHistoryAPIInstance->lookUpHistoryReport($carfax_account_token_as_array['value'], $data["vin"]);

                $carfax_log = [];
                $carfax_log['vin'] = $data['vin'];
                $carfax_log['stock_no'] = empty($data['stock_no']) ? '' : $data['stock_no'];
                $carfax_log['action'] = "SEARCH";
                $carfax_log['user'] = service('authPolicy')->currentUserID();
                $carfax_log['response'] = empty($response['data']) ? '' : json_encode($response['data']);
                $carfax_log['status'] = isset($response['success']) ? 'SUCCESS' : 'ERROR';

                $this->carfax_order_log_repository->create($carfax_log);


                if (empty($response['errors'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Carfax Account Token Missing."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function orderReport()
    {
        $data = $this->request->getPost();
        $validated = $this->validate([
            'vin'  => [
                "label" => 'Vin',
                "rules" => 'required'
            ],
            'report_type'  => [
                "label" => 'Report Type',
                "rules" => 'required|is_natural'
            ],
            'lien_express_province' => [
                "label" => 'Lien Express Province',
                "rules" => 'permit_empty'
            ],
            'stock_no' => [
                "label" => 'Reference',
                "rules" => 'permit_empty'
            ]

        ]);

        if (service('authPolicy')->hasManagerRole() || service('authPolicy')->hasOwnerRole()) {

            if ($validated) {

                $carfax_account_token_as_array = $this->configuration_controller->findConfigurationColumnsByKeyAsArray('carfax_account_token', ['value']);
                if (!empty($carfax_account_token_as_array['value'])) {
                    $report_type = (int) $data["report_type"];
                    $options = $data;

                    $carfax_log = [];
                    $carfax_log['vin'] = $data['vin'];
                    $carfax_log['stock_no'] = empty($data['stock_no']) ? '' : $data['stock_no'];
                    $carfax_log['action'] = "ORDER";
                    $carfax_log['user'] = service('authPolicy')->currentUserID();

                    unset($options['vin']);
                    unset($options['report_type']);
                    $options["LienExpressProvince"] = empty($data['lien_express_province']) ? '' : $data['lien_express_province'];
                    $options["RefNum"] = empty($data['stock_no']) ? '' : $data['stock_no'];


                    $response = $this->carHistoryAPIInstance->orderVehicleHistoryReport($carfax_account_token_as_array['value'], $data["vin"], $report_type, $options);

                    $carfax_log['response'] = empty($response['data']) ? '' : json_encode($response['data']);
                    $carfax_log['status'] = isset($response['success']) ? 'SUCCESS' : 'ERROR';
                    $this->carfax_order_log_repository->create($carfax_log);


                    if (empty($response['errors'])) {
                        $this->response->setContentType('application/json')->setJSON($response)->send();
                    } else {
                        $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                    }
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Carfax Account Token Missing."])->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function isCarfaxAPIAvailable()
    {
        $carfax_account_token_as_array = $this->configuration_controller->findConfigurationColumnsByKeyAsArray('carfax_account_token', ['value']);
        $carfax_vehicle_history_api_as_array = $this->configuration_controller->findConfigurationColumnsByKeyAsArray('carfax_vehicle_history_api', ['value']);

        if (!empty($carfax_account_token_as_array['value']) && !empty($carfax_vehicle_history_api_as_array['value'])) {
            $this->response->setContentType('application/json')->setJSON(['is_available' => true])->send();
        } else {
            $this->response->setContentType('application/json')->setJSON(['is_available' => false])->send();
        }
    }


    public function getLogs()
    {

        $items = 100;
        $paginate = true;
        $query = null;


        $column = 'created_at';
        $order = 'DESC';

        if (count($_GET) > 0) {
            $query = $this->carfax_order_log_repository->prepareDataForFilter($_GET);
        }


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->carfax_order_log_repository;

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->carfax_order_log_repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'carfax_order_log_view_id', 'carfax_order_log_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }
}
