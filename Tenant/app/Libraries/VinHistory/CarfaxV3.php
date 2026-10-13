<?php

namespace Tenant\app\Libraries\VinHistory;

use CodeIgniter\Cache\CacheFactory;
use Config\NonPersistantCache;

/**
 * CarfaxV3 This method includes the Carfax V3 API for pulling the history report for the client
 */
class CarfaxV3
{

    private string $client_id;
    private string $client_secret;

    /**
     * cache_factory
     *
     * @var \CodeIgniter\Cache\CacheInterface
     */
    protected $cache_factory;

    public function __construct()
    {
        $this->client_id = getEnv("carfax.oauth.client_id");
        $this->client_secret = getEnv("carfax.oauth.client_secret");

        $this->cache_factory = CacheFactory::getHandler(new NonPersistantCache());
    }

    protected function getToken(): string
    {

        if (empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://authentication.carfax.ca/oauth/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'audience=https%3A%2F%2Fapi.carfax.ca&grant_type=client_credentials&client_id=' . $this->client_id . '&client_secret=' . $this->client_secret);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);

            try {
                $response = json_decode($response, true);


                if (!empty($response['access_token'])) {
                    $this->cache_factory->save('ZD_CARFAX_ACCESS_TOKEN', $response['access_token'], 60 * 105);
                }
            } catch (\Exception $ex) {
                log_message('alert', $ex->getMessage());
            }

            curl_close($ch);
        }

        return $this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN');
    }

    /**
     * getCarfaxReport This method accepts accepts account number of the tenant and VIN for which the report
     * is to be retrieved
     *
     * @param  String $account_no
     * @param  String $vin
     * @param  String $hide_vin
     * @return array
     */
    public function getCarfaxReport(String $account_no, String $vin, String $hide_vin = "false"): array
    {
        try {

            $access_token = $this->getToken();

            if (!empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {

                $url = 'https://badgingapi.carfax.ca/api/v3/badges?Vin=' . $vin . '&CompanyId=' . $account_no . '&language=EN'
                    . '&HideVin=' . $hide_vin;

                $headers = [
                    'Accept: application/json',
                    "Authorization: Bearer $access_token"
                ];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $result = json_decode($result, true);

                $response_message = !empty($result['ResponseData']['Badges'][0]['ResultMessage']) ? $result['ResponseData']['Badges'][0]['ResultMessage'] : '';

                if (!empty($response_message)) {
                    if ($response_message == 'Successful') {

                        $report_url = !empty($result['ResponseData']['Badges'][0]['VhrReportUrl']) ? $result['ResponseData']['Badges'][0]['VhrReportUrl'] : '';
                        $badge_url = !empty($result['ResponseData']['Badges'][0]['BadgesImageUrl']) ? $result['ResponseData']['Badges'][0]['BadgesImageUrl'] : '';

                        return [
                            'success' => 'Successful',
                            'VhrReportUrl' => $report_url,
                            'BadgesImageUrl' => $badge_url,
                        ];
                    } else {
                        return ['errors' => 'No Results'];
                    }
                } else {
                    return ['errors' => 'No Results'];
                }
            }
        } catch (\Exception $ex) {
            log_message('info', "Some error was there in processing this request : " + $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }

        return ['errors' => 'ZD_CARFAX_ACCESS_TOKEN Missing'];
    }

    public function lookUpHistoryReport(String $account_token, String $vin): array
    {
        $error_message = 'There were some errors.';

        if (empty($account_token)) {
            return ['errors' => 'Account Number Or Account Token Missing'];
        }

        try {

            $access_token = $this->getToken();
            if (!empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {

                $url = "https://vhrlookupapi.carfax.ca/api/v4/ReportLookup/ReportLookupForProxy";

                $headers = [
                    'Accept: application/json',
                    "Authorization: Bearer $access_token",
                    "Content-Type: application/json",
                ];

                $payload = [];
                $payload["Vin"] = $vin;
                $payload["AccountToken"] = $account_token;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return ['errors' => $error_message];
                }
                curl_close($ch);

                $response = json_decode($result, true);

                log_message('info', $result);

                if (!empty($response)) {
                    if (!empty($response['ResponseData'])) {
                        if (!empty($response['ResponseData']['AccountToken'])) {
                            unset($response['ResponseData']['AccountToken']);
                        }
                    }

                    switch ($response['ResultCode']) {
                        case -10:
                            return ["data" => !empty($response['ResponseData']) ? $response['ResponseData'] : [], "success" => "No History for " . $vin];
                            break;
                        case -5:
                            return ["errors" => "Error with VIN or Account Token."];
                        case 1:
                            return ["data" => !empty($response['ResponseData']) ? $response['ResponseData'] : [], "success" => "Successful"];
                        default:
                            break;
                    }
                }

                return ['errors' => $error_message];
            }
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }

        return ['errors' => 'ZD_CARFAX_ACCESS_TOKEN Missing'];
    }

    public function orderVehicleHistoryReport(String $account_token, String $vin, int $report_type, array $options = [])
    {
        $payload = $options;
        $payload["ReportType"] = $report_type;

        $error_message = 'There were some errors.';

        if (empty($account_token)) {
            return ['errors' => 'Account Number Or Account Token Missing'];
        }

        try {

            $access_token = $this->getToken();

            if (!empty($this->cache_factory->get('ZD_CARFAX_ACCESS_TOKEN'))) {
                $url = "https://vhrorderapi.carfax.ca/Api/V2/Order/VhrForProxy";

                $headers = [
                    'Accept: application/json',
                    "Authorization: Bearer $access_token",
                    "Content-Type: application/json",
                ];

                $payload["Vin"] = $vin;
                $payload["AccountToken"] = $account_token;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    $error_message = curl_error($ch);
                    curl_close($ch);
                    return ['errors' => $error_message];
                }
                curl_close($ch);

                $response = json_decode($result, true);

                log_message('info', $result);

                if (!empty($response)) {
                    if (!empty($response['ResponseData'])) {
                        if (!empty($response['ResponseData']['AccountToken'])) {
                            unset($response['ResponseData']['AccountToken']);
                        }
                    }

                    switch ($response['ResultCode']) {
                        case -5:
                            return ["errors" => "Error with VIN or Account Token."];
                        case 1:
                            return ["data" => !empty($response['ResponseData']) ? $response['ResponseData'] : [], "success" => "Successful"];
                        default:
                            break;
                    }
                }

                return ['errors' => $error_message];
            }
        } catch (\Exception $ex) {
            log_message('info', $ex->getMessage());
            return ['errors' => $ex->getMessage()];
        }

        return ['errors' => 'ZD_CARFAX_ACCESS_TOKEN Missing'];
    }
}
