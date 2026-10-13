<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\Valuation\Carfax;

final class ValuationController extends \App\Controllers\BaseController
{
    private Carfax $carfax;

    public function __construct()
    {
        $this->carfax = Carfax::create();
    }

    public function lookUpVehicleByYearMakeModel()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'year'  => [
                "label" => 'Year',
                "rules" => 'required'
            ],
            'make'  => [
                "label" => 'Make',
                "rules" => 'required_with[model]'
            ],
            'model'  => [
                "label" => 'Model',
                "rules" => 'permit_empty'
            ],
            'postal_code' => [
                "label" => 'Postal Code',
                "rules" => 'permit_empty|alpha_numeric_space'
            ],
            'odometer'  => [
                "label" => 'Odometer',
                "rules" => 'required|is_natural|greater_than_equal_to[500]|less_than_equal_to[500000]'
            ],
        ]);

        if ($validated) {
            if (isset($data['vin'])) {
                unset($data['vin']);
            }

            foreach ($data as $key => $value) {
                $data[ucfirst($key)] = $value;
                unset($data[$key]);
            }

            $response = $this->carfax->lookupVehicle($data);
            if (!empty($response['errors']) || !empty($response['error'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function lookUpVehicleByYearMakeModelAndTrim()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'year'  => [
                "label" => 'Year',
                "rules" => 'required'
            ],
            'make'  => [
                "label" => 'Make',
                "rules" => 'required'
            ],
            'model'  => [
                "label" => 'Model',
                "rules" => 'required'
            ],
            'trim'  => [
                "label" => 'Trim',
                "rules" => 'required'
            ],
            'postal_code' => [
                "label" => 'Postal Code',
                "rules" => 'permit_empty|alpha_numeric_space'
            ],
            'odometer'  => [
                "label" => 'Odometer',
                "rules" => 'required|is_natural|greater_than_equal_to[500]|less_than_equal_to[500000]'
            ],
        ]);

        if ($validated) {
            if (isset($data['vin'])) {
                unset($data['vin']);
            }

            foreach ($data as $key => $value) {
                $data[ucfirst($key)] = $value;
                unset($data[$key]);
            }

            $response = $this->carfax->lookupVehicle($data);
            if (!empty($response['errors']) || !empty($response['error'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function lookupVehicleByVin()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'vin'  => [
                "label" => 'VIN',
                "rules" => 'required|alpha_numeric|exact_length[17]'
            ],
            'postal_code' => [
                "label" => 'Postal Code',
                "rules" => 'permit_empty|alpha_numeric_space'
            ],
            'odometer'  => [
                "label" => 'Odometer',
                "rules" => 'required|is_natural|greater_than_equal_to[500]|less_than_equal_to[500000]'
            ],
        ]);

        if ($validated) {
            $response = $this->carfax->lookupVehicle($data);
            if (!empty($response['errors']) || !empty($response['error'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function lookupVehicleByTrimAndVIN()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'vin'  => [
                "label" => 'VIN',
                "rules" => 'required|alpha_numeric|exact_length[17]'
            ],
            'postal_code' => [
                "label" => 'Postal Code',
                "rules" => 'permit_empty|alpha_numeric_space'
            ],
            'odometer'  => [
                "label" => 'Odometer',
                "rules" => 'required|is_natural|greater_than_equal_to[500]|less_than_equal_to[500000]'
            ],
            'trim'  => [
                "label" => 'Trim',
                "rules" => 'required'
            ],
        ]);

        if ($validated) {
            $response = $this->carfax->lookupVehicle($data);
            if (!empty($response['errors']) || !empty($response['error'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function valuation()
    {
        $data = $this->request->getPost();
        $validated = $this->validate([
            'year'  => [
                "label" => 'Year',
                "rules" => 'permit_empty|required_without[vin]'
            ],
            'make'  => [
                "label" => 'Make',
                "rules" => 'permit_empty|required_without[vin]'
            ],
            'model'  => [
                "label" => 'Model',
                "rules" => 'permit_empty|required_without[vin]'
            ],
            'vin'  => [
                "label" => 'VIN',
                "rules" => 'permit_empty|required_without[year,make,model]|alpha_numeric'
            ],
            'trim'  => [
                "label" => 'Trim',
                "rules" => 'required'
            ],
            'odometer'  => [
                "label" => 'Odometer',
                "rules" => 'required|greater_than_equal_to[500]|less_than_equal_to[500000]'
            ],
            'engine'  => [
                "label" => 'Engine',
                "rules" => 'required'
            ],
            'fuel_type'  => [
                "label" => 'Fuel type',
                "rules" => 'required'
            ],
            'drivetrain'  => [
                "label" => 'Drivetrain',
                "rules" => 'required'
            ],
            'transmission'  => [
                "label" => 'Transmission',
                "rules" => 'required'
            ],
            'postal_code' => [
                "label" => 'Postal Code',
                "rules" => 'permit_empty|alpha_numeric_space'
            ]
        ]);

        if ($validated) {
            if (empty($data['postal_code'])) {
                unset($data['postal_code']);
            } else {
                $data['postalCode'] = $data['postal_code'];
                unset($data['postal_code']);
            }

            $data['valuationTypeRequest'] = 2;
            $data['fuelType'] = $data['fuel_type'];
            unset($data['fuel_type']);

            $response = $this->carfax->valuation($data);
            if (!empty($response['errors']) || !empty($response['error'])) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }
}
