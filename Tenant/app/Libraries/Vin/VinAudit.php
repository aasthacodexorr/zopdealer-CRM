<?php

namespace Tenant\app\Libraries\Vin;
use Tenant\app\Libraries\Mapper\Zop;

use Override;

/**
 * VinAudit This defines the process of request to VinAudit API for the VIN related data.
 */
class VinAudit extends VinAbstract
{

    protected $url = 'https://specifications.vinaudit.com/v3/specifications?';

    private string $key;
    private Zop $zop_mapper;
    // CURL Settings
    private $curl_time = 60;
    private $curl_return_transfer = 1;

    private $errors = array(
        'VIN_UNDEFINED' => 'VIN Number must not be null',
        'TRIM_ID_UNDEFINED' => 'Valid Trim ID is required',
        'BAD_RESPONSE' => 'Response returned does not appear to be valid.',
    );

    public function __construct()
    {
        $this->key = getEnv('vindecoder.vinaudit.key');
        $this->zop_mapper = new Zop();
    }

    public function lookupVehicleByVin(string $vin): void
    {
        if ($vin == null) {
            $this->formatted_response = ["error" => $this->errors['VIN_UNDEFINED']];
            exit;
        } else {
            //CURL call for json response of vin
            $data = $this->_call('vin', $vin);
            if (!empty($data['success'])) {
                $this->format();
            } else {
                $this->formatted_response = ["error" => $this->errors['BAD_RESPONSE']];
            }
        }
    }

    public function lookupVehicleByTrimID(String $trim_id): void
    {
        if ($trim_id == null) {
            $this->formatted_response = ["error" => $this->errors['TRIM_ID_UNDEFINED']];
            exit;
        } else {
            //CURL call for json response of trim_id
            $data = $this->_call('id', $trim_id);
            if ($data['success']) {
                $this->format();
            } else {
                $this->formatted_response = ["error" => $this->errors['BAD_RESPONSE']];
            }
        }
    }

    public function format(): void
    {

        $this->formatted_response['vin_company'] = '2';

        //vin 
        isset($this->response['input']['vin']) ? $this->formatted_response['vin'] = $this->response['input']['vin'] : '';

        //year
        isset($this->response['attributes']['year']) ? $this->formatted_response['year'] = $this->response['attributes']['year'] : '';

        //make
        isset($this->response['attributes']['make']) ? $this->formatted_response['make'] = $this->response['attributes']['make'] : '';

        //model
        isset($this->response['attributes']['model']) ? $this->formatted_response['model'] = $this->response['attributes']['model'] : '';

        //available selections
        $this->formatted_response['selections'] = [];

        if (count($this->response['selections']['trims']) > 0) {
            foreach ($this->response['selections']['trims'] as $trim) {
                $styles = $trim['styles'];
                $trim['trim_id'] = $trim['id'];
                $trim['style_id'] = $trim['id'];
                $trim['selected'] = !empty($trim['selected']);
                $trim['style'] = '';
                unset($trim['id']);
                unset($trim['styles']);


                if (count($styles) > 0) {
                    foreach ($styles as $style) {
                        $trim['selected'] = !empty($style['selected']);
                        $trim['style_id'] = $style['id'];
                        $trim['style'] = $style['name'];
                        array_push($this->formatted_response['selections'], $trim);
                    }
                } else {
                    array_push($this->formatted_response['selections'], $trim);
                }
            }
        }

        //trim
        if (count($this->response['selections']['trims']) > 0) {
            $this->formatted_response['trim'] = array_map(function ($each_trim) {
                return $each_trim['name'];
            }, $this->response['selections']['trims']);
        }

        //door
        isset($this->response['attributes']['doors']) ? $this->formatted_response['door'] = [$this->response['attributes']['doors']] : '';

        //drivetrain
        isset($this->response['attributes']['drivetrain']) ? $this->formatted_response['drivetrain'] = [$this->response['attributes']['drivetrain']] : '';

        //body_type
        isset($this->response['attributes']['type'])? $this->formatted_response['body_type'] = $this->zop_mapper->exactMatch('body_types',$this->response['attributes']['type'],$this->response['attributes']['type']): '';  

        //stock_image
        if (count($this->response['photos']) > 0) {
            $this->formatted_response['stock_image'] = array_map(function ($image) {
                return $image['url'];
            }, $this->response['photos']);
        }

        //engine
        isset($this->response['attributes']['engine_cylinders']) ? $this->formatted_response['engine'] = [$this->response['attributes']['engine_cylinders'] . ' Cylinder Engine'] : '';

        //litres
        isset($this->response['attributes']['engine_size']) ? $this->formatted_response['litres'] = [$this->response['attributes']['engine_size']] : '';

        //transmission
        isset($this->response['attributes']['transmission_type']) ? $this->formatted_response['transmission'] = [$this->response['attributes']['transmission_type']] : '';

        //fuel_type
        if (!empty($this->response['attributes']['fuel_type'])) {
            if (strpos($this->response['attributes']['fuel_type'], "Electric") !== false) {
                $this->formatted_response['fuel_type'] = ["Electric Battery"];
                $this->formatted_response['engine'] = ["Electric"];
            } else {
                $this->formatted_response['fuel_type'] = [$this->response['attributes']['fuel_type']];
            }
        } else {
            $this->formatted_response['fuel_type'] = '';
        }

        //fuel_city
        if (!empty($this->response['attributes']['city_mileage'])) {
            $fuel_city = (int)(explode(' ', $this->response['attributes']['city_mileage'])[0]);
            $this->formatted_response['fuel_city'] = [];
            if (!empty($fuel_city)) {
                $this->formatted_response['fuel_city'] = [235.215 / $fuel_city];
            }
        }


        //fuel_hwy
        if (!empty($this->response['attributes']['highway_mileage'])) {
            $fuel_hwy = (int)(explode(' ', $this->response['attributes']['highway_mileage'])[0]);
            $this->formatted_response['fuel_hwy'] = [];
            if (!empty($fuel_hwy)) {
                $this->formatted_response['fuel_hwy'] = [235.215 / $fuel_hwy];
            }
        }

        if (!empty($this->response['attributes']['standard_seating'])) {
            $this->formatted_response['passengers'] = is_numeric($this->response['attributes']['standard_seating']) ? [$this->response['attributes']['standard_seating'] . ' Seater'] : ['Other'];
        }


        isset($this->formatted_response['fuel_city']) ? $this->formatted_response['fuel_city'] = array_map(function ($number) {
            return number_format($number, 2);
        }, $this->formatted_response['fuel_city']) : '';
        isset($this->formatted_response['fuel_hwy']) ? $this->formatted_response['fuel_hwy'] = array_map(function ($number) {
            return number_format($number, 2);
        }, $this->formatted_response['fuel_hwy']) : '';

        if (count($this->response['equipments']) > 0) {
            foreach ($this->response['equipments'] as $equipment) {
                $value = $equipment['value'] == '' ? 1 : $equipment['value'];
                $availability = $equipment['availability'] == '' ? 'technical_specification' : $equipment['availability'];

                if (strtolower($availability) == 'standard' && ($value != 1)) {
                    $equipment['name'] = $equipment['name'] . ' - ' . $value;
                    $value = 1;
                }

                if ($availability != 'technical_specification') {
                    $value = $this->default_value;
                }

                $this->formatted_response[strtolower($availability)][$equipment['group']][$equipment['name']] = $value;
            }
        }

        return;
    }

    #[Override]
    public function getFormattedResponse(): array
    {
        return $this->formatted_response;
    }

    private function _call(String $search_key, String $search_value)
    {
        // Initialize curl
        $ch = curl_init();
        $url = $this->url . 'format=json&key=';
        $url .= $this->key;
        $url .= '&' . $search_key . '=';
        $url .= $search_value;
        $url .= '&include=selections,attributes,equipment,colors,recalls,warranties,photos';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->curl_return_transfer);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_time);

        // Get result and format it
        $result = curl_exec($ch);
        curl_close($ch);

        $this->response = json_decode($result, true);
        return $this->response;
    }
}
