<?php

namespace App\Libraries\KBB;

/**
 * KBB  This library is to call KBB API
 */
class KBB
{
    /**
     * API_KEY  This is the KEY provided by KBB to call their APIs.
     *
     * @var String
     */
    private $API_KEY;

    /**
     * API_URL  This is the URL provided by KBB to call their APIs
     *
     * @var mixed
     */
    private $API_URL;

    /**
     * Set aPI_KEY This is the KEY provided by KBB to call their APIs.
     *
     * @param  String  $API_KEY  API_KEY This is the KEY provided by KBB to call their APIs.
     *
     * @return  self
     */
    public function setAPI_KEY(String $API_KEY)
    {
        $this->API_KEY = $API_KEY;

        return $this;
    }

    /**
     * Set aPI_URL This is the URL provided by KBB to call their APIs
     *
     * @param  mixed  $API_URL  API_URL This is the URL provided by KBB to call their APIs
     *
     * @return  self
     */
    public function setAPI_URL($API_URL)
    {
        $this->API_URL = $API_URL;

        return $this;
    }

    /**
     * Get API_KEY This is the KEY provided by KBB to call their APIs.
     *
     * @return  String
     */
    public function getAPI_KEY()
    {
        if (empty($this->API_KEY)) {
            throw new  \Exception("Please set API KEY For KBB.", 1);
        }
        return $this->API_KEY;
    }

    /**
     * Get API_URL This is the URL provided by KBB to call their APIs
     *
     * @return  mixed
     */
    public function getAPI_URL()
    {
        if (empty($this->API_KEY)) {
            throw new  \Exception("Please set API Base URL For KBB.", 1);
        }
        return $this->API_URL;
    }

    /**
     * getAllYears
     *
     * @return void
     */
    public function getAllYears(): ?array
    {
        $response = [];
        try {
            $response = $this->callKBBAPI("/Gps/GetAllYears");
            if ($response['ResponseStatus'] == 1) {
                if (is_array($response['Response'])) {
                    return array_map(function ($item) {
                        return ['label' => $item['Year'], 'value' => $item['Year']];
                    }, (array) $response['Response']);
                }
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * getAllMakes
     *
     * @param  string $year
     * @return array
     */
    public function getAllMakes(string $year = null): ?array
    {
        $response = [];
        try {

            $parameter_string = "&language=en-CA";

            if (!empty($year)) {
                $parameter_string .= "&year=";
                $parameter_string .= $year;
            }

            $response = $this->callKBBAPI("/Gps/GetAllVehicleBrand", $parameter_string);

            if ($response['ResponseStatus'] == 1) {
                if (is_array($response['Response'])) {
                    return array_map(function ($item) {
                        return ['label' => $item['Name'], 'value' => $item['ID']];
                    }, (array) $response['Response']);
                }
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * getAllModels This method provides all the model for a particular makes
     *
     * @param  mixed $year
     * @param  mixed $makeID
     * @return array
     */
    public function getAllModels(string $year = null, $makeID = null): ?array
    {
        $response = [];

        try {

            $parameter_string = "&language=en-CA";

            if (!empty($year)) {
                $parameter_string .= "&year=";
                $parameter_string .= $year;
            }

            if (!empty($makeID)) {
                $parameter_string .= "&brandID=";
                $parameter_string .= $makeID;
            }

            $response = $this->callKBBAPI("/Gps/GetAllVehicleModel", $parameter_string);

            if ($response['ResponseStatus'] == 1) {
                if (is_array($response['Response'])) {
                    return array_map(function ($item) {
                        return ['label' => $item['Name'], 'value' => $item['ID']];
                    }, (array) $response['Response']);
                }
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * getAllTrims This method provides all the trims for a particular modelID
     *
     * @param  mixed $year
     * @param  mixed $modelID
     * @return array
     */
    public function getAllTrims(string $year = null, $modelID = null): ?array
    {
        $response = [];
        try {

            $parameter_string = "&language=en-CA";

            if (!empty($year)) {
                $parameter_string .= "&year=";
                $parameter_string .= $year;
            }

            if (!empty($modelID)) {
                $parameter_string .= "&modelID=";
                $parameter_string .= $modelID;
            }

            $response = $this->callKBBAPI("/Gps/GetAllVehicleVersion", $parameter_string);

            if ($response['ResponseStatus'] == 1) {
                if (is_array($response['Response'])) {
                    return array_map(function ($item) {
                        return ['label' => $item['Name'], 'value' => $item['KBBID']];
                    }, (array) $response['Response']);
                }
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * getVehiclePrice
     *
     * @param  string $price_type
     * @param  string $vehicle_condition_grade
     * @param  string $vehicleId
     * @param  string $mileage
     * @param  string $stateId
     * @return array
     */
    public function getVehiclePrice(
        int $price_type,
        string $vehicle_condition_grade,
        string $vehicleId,
        string $mileage = null,
        string $stateId = null
    ): ?array {
        $response = [];
        try {

            $parameter_string = "&language=en-CA";

            if (!empty($price_type)) {
                $parameter_string .= "&vehiclePriceTypeID=";
                $parameter_string .= $price_type;
            }

            if (!empty($vehicle_condition_grade)) {
                $parameter_string .= "&vehicleGradeID=";
                $parameter_string .= $vehicle_condition_grade;
            }

            if (!empty($vehicleId)) {
                $parameter_string .= "&vehicleID=";
                $parameter_string .= $vehicleId;
            }

            if (!empty($mileage)) {
                $parameter_string .= "&mileage=";
                $parameter_string .= $mileage;
            }

            if (!empty($stateId)) {
                $parameter_string .= "&locationStateID=";
                $parameter_string .= $stateId;
            }

            $response = $this->callKBBAPI("/Gps/GetVehiclePrices", $parameter_string);

            if ($response['ResponseStatus'] == 1) {
                if (is_array($response['Response'])) {
                    return [
                        'minimum_price' => round($response['Response']['PriceLow']),
                        'maximum_price' => round($response['Response']['PriceHigh']),
                        'average_price' => round($response['Response']['BaseDiscountedPrice']),
                    ];
                }
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * getVehiclePriceAdvisory
     *
     * @param  string $price_type
     * @param  string $vehicle_condition_grade
     * @param  string $vehicleId
     * @param  string $mileage
     * @param  string $stateId
     * @return array
     */
    public function getVehiclePriceAdvisory(
        int $price_type,
        string $vehicle_condition_grade,
        string $vehicleId,
        string $mileage = null,
        string $stateId = null
    ): ?array {
        $response = [];
        try {

            $parameter_string = "&language=en-CA";

            if (!empty($price_type)) {
                $parameter_string .= "&vehiclePriceTypeID=";
                $parameter_string .= $price_type;
            }

            if (!empty($vehicle_condition_grade)) {
                $parameter_string .= "&vehicleGradeID=";
                $parameter_string .= $vehicle_condition_grade;
            }

            if (!empty($vehicleId)) {
                $parameter_string .= "&vehicleID=";
                $parameter_string .= $vehicleId;
            }

            if (!empty($mileage)) {
                $parameter_string .= "&mileage=";
                $parameter_string .= $mileage;
            }

            if (!empty($stateId)) {
                $parameter_string .= "&locationStateID=";
                $parameter_string .= $stateId;
            }

            $response = $this->callKBBAPI("/Gps/GetPriceAdvisor", $parameter_string);

            if ($response['ResponseStatus'] == 1) {
                    return [
                        'price_advisory' => $response['Response']
                    ];
            }
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }

        return ["errors" => $response['Response']];
    }

    /**
     * callKBBAPI   This is a simple class that calls APIs of KBB to retrieve data.
     *
     * @param  string $relative_url
     * @param  string $parameter_string
     * @return string
     */
    private function callKBBAPI(string $relative_url, string $parameter_string = ""): ?array
    {
        $url =  $this->getAPI_URL() . $relative_url . "?securityToken=" . $this->getAPI_KEY();

        if (!empty($parameter_string)) {
            $url .= $parameter_string;
        }

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "accept: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp, true);
    }
}
