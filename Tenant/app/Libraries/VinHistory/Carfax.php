<?php

namespace Tenant\app\Libraries\VinHistory;


/**
 * Carfax This method includes the Carfax API for pulling the history report for the client
 */
class Carfax
{

    /**
     * token This is a webservice token provided by Carfax for us.
     *
     * @var string
     */
    private $token = 'BE/s9XcA33ZXLiTl/ak3oXSu+pvkuewrU18T6xZChc9v/JYbMyYfAB3a0algBvj+q80PGUAjsl9UAwHD4BJxuYmsLaxaP3UlQyOjiDfSmDA7Jq8OphhSoDSmxycFIK7RV5tl1HTie6tGM9R6HLQgbxTTz03chsVY4LpWM7reM1FsSA1y/pbMsVVDTj7wx/ZBuYGh+0TLhdbEZD+N+g7TQVUkU2kxONCZ5T2Gi+0u7wwP7m/h/mgQHIzDYYQAe8F4f4aqhoGD4do7h3wwyKu2siNQZNqrauXD1utJLVGuU2sOOxB8XXRtopaM1szzG1cr2oMEZWx1cGZuqCFT+RVzdt7EqQ+p43JIdT0+aMTgZZJe8K2y8oex1JgSoi+WRFcA';


    /**
     * getCarfaxReport This method accepts accepts account number of the tenant and VIN for which the report
     * is to be retrieved
     *
     * @param  String $account_no
     * @param  String $vin
     * @param  String hide_vin
     * @return array
     */
    public function getCarfaxReport(String $account_no, String $vin, String $hide_vin = "false"): array
    {
        try {
            $url = 'https://badgingapi.carfax.ca/badges/' . $vin . '?accountNumber=' . $account_no . '&language=EN';

            $headers = [
                'Accept: application/json',
                "WebServiceToken: $this->token"
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
            if (!empty($result['ResultMessage'])) {
                if ($result['ResultMessage'] == 'Successful') {
                    return [
                        'success' => 'Successful',
                        'VhrReportUrl' => $result['BadgesList'][0]['VhrReportUrl'],
                        'BadgesImageUrl' => $result['BadgesList'][0]['BadgesImageUrl']
                    ];
                } else {
                    return ['errors' => 'No Results'];
                }
            } else {
                return ['errors' => 'No Results'];
            }
        } catch (\Exception $ex) {
            echo "Some error was there in processing this request : " + $ex->getMessage();
        }

        return ['errors' => 'No Results'];
    }
}
