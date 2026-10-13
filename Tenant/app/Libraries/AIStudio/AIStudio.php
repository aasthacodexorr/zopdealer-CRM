<?php

namespace Tenant\app\Libraries\AIStudio;

class AIStudio
{

    public static function process(string $vehicle_image, string $background_image_url, array $options = []): ?array
    {
        try {

            $mask_number_plate = !empty($options['mask_number_plate']) ? 'true' : 'false';
            $show_shadow = !empty($options['show_shadow']) ? 'true' : 'false';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, getEnv('aistudio.host') . '/render');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                "car_url=" . $vehicle_image . "&background_url=" . $background_image_url . "&mask_number_plate=" . $mask_number_plate . "&show_shadow=" . $show_shadow . ""
            );

            $response = curl_exec($ch);

            curl_close($ch);

            $response = json_decode($response, true);

            return $response;
        } catch (\Exception $ex) {
            return [
                'errors' => $ex->getMessage()
            ];
        }
    }
}
