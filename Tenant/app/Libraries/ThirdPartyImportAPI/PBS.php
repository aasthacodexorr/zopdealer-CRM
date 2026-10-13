<?php

namespace Tenant\app\Libraries\ThirdPartyImportAPI;

set_time_limit(0);

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use Error;
use GuzzleHttp\Client;
use Tenant\app\Controllers\InventoryImportController;
use Tenant\app\Libraries\Mapper\Zop;
use Tenant\app\Libraries\ThirdPartyInventory\ThirdPartyAdapterInterface;
use Tenant\app\Repositories\ConfigurationRepository;

final class PBS implements ThirdPartyAdapterInterface
{

    private string $saved_local_file_path = '';
    private string $cdn_file_path = '';

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    private string $pbs_serial_number;
    private string $pbs_available_access;

    private string $database;

    public function __construct()
    {
        $this->saved_local_file_path = $this->generateFilePath();
        $this->cdn_file_path = str_replace(WRITEPATH, '', $this->saved_local_file_path);
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function checkAuthorization(): bool
    {

        try {
            $pbs_serial_number_object = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('pbs_serial_number', ['value']);
            $pbs_available_access_object = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('pbs_available_access', ['value']);

            $this->pbs_serial_number = $pbs_serial_number_object['value'];
            $this->pbs_available_access = $pbs_available_access_object['value'];

            if (!empty($this->pbs_serial_number) && stripos($this->pbs_available_access, "VehicleGet")  !== false) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function savePayload(): void
    {

        try {
            if (!$this->checkAuthorization()) {
                throw new \Exception(ERROR_RESOURCE_PERMISSION, 401);
            }
            $client = new Client([
                'timeout'  => 0
            ]);

            if (!file_exists($this->getLocalWritableFolderPath())) {
                mkdir($this->getLocalWritableFolderPath(), 0777, true);
            }

            $response = $client->request('post', 'https://partnerhub.pbsdealers.com/json/reply/VehicleGet', [
                'connect_timeout' => 0,
                'read_timeout' => 0,
                'sink' => $this->saved_local_file_path,
                'auth' => [getEnv('pbs.username'), getEnv('pbs.password')],
                'json' => ['SerialNumber' => $this->pbs_serial_number]
            ]);
            $status = $response->getStatusCode();

            if (file_exists($this->saved_local_file_path)) {
                $this->saveFileOnCDN($this->saved_local_file_path, $this->cdn_file_path);
            }

            //TODO Save logs 
            if ($status !== 200) {
                throw new \Exception(ERROR_RESOURCE_PERMISSION, 401);
            }
        } catch (\Exception $ex) {
            //TODO Save logs with exception
            CLI::write($ex->getMessage());
        }
    }

    public function processInventories(): void
    {
        $inventory_import_controller =  new InventoryImportController();
        $file = null;
        $stream = null;
        try {
            if (!file_exists($this->saved_local_file_path)) {
                throw new Error('We were not able to download the data from PBS', 400);
            }

            $file = fopen($this->saved_local_file_path, 'rb');
            $stream = new \React\Stream\ReadableResourceStream($file);

            $file_content = '';
            $stream->on('data', function ($data) use (&$file_content, $inventory_import_controller): void {

                for ($i = 0; $i < strlen($data); $i++) {
                    $file_content = $file_content . $data[$i];
                    $file_content = str_replace('{"Vehicles":[', "", $file_content);
                    if (trim($file_content) == ",") {
                        $file_content = str_replace(',', "", $file_content);
                    }

                    $json = json_decode($file_content, true);
                    if ($json) {
                        $response = $inventory_import_controller->thirdPartyPreprocessAdapter($json, $this);
                        CLI::write(json_encode($response));
                        $file_content = '';

                        //TODO Save the Response Logs
                    }
                }

                CLI::write("\n\r Writing Complete \n\r");
                CLI::write("\n\r");
            });

            $stream->on('end', function () use ($file, $inventory_import_controller): void {
                $inventory_import_controller->postProcessing(db_connect()->getDatabase());

                if (is_resource($file)) {
                    fclose($file);
                }

                if (file_exists($this->saved_local_file_path)) {
                    unlink($this->saved_local_file_path);
                }
            });
            $stream->on('error', function (\Exception $e) use ($file, $stream, $inventory_import_controller): void {
                $stream->close();
                if (is_resource($file)) {
                    fclose($file);
                }

                if (file_exists($this->saved_local_file_path)) {
                    unlink($this->saved_local_file_path);
                }
            });
        } catch (\Exception $ex) {
            if (is_resource($file)) {
                fclose($file);
            }

            if (file_exists($this->saved_local_file_path)) {
                unlink($this->saved_local_file_path);
            }
        }
    }

    public function preprocess(array $payload, array $optional_data = []): array
    {
        $date_received_object = Carbon::now();
        $zop_mapper = new Zop();

        $mapper = [
            [
                "inventory_attribute" => "year",
                "payload_attribute" => "Year",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "model",
                "payload_attribute" => "Model",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "trim",
                "payload_attribute" => "Trim",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "vin",
                "payload_attribute" => "VIN",
                "type" => "string",
            ],
            [
                "inventory_attribute" => "odometer",
                "payload_attribute" => "Odometer",
                "type" => "numeric",
            ],
            [
                "inventory_attribute" => "price",
                "payload_attribute" => "Retail",
                "type" => "string",
            ]

        ];

        $optional_attributes = [
            'status',
            'sub_status',
            'feature_listing',
            'vehicle_type',
            'visibility',
            'odometer',
            'special_price',
            'price',
            'location',
            'title',
            'vehicle_description',
            'financing_on',
            'downpayment',
            'interest_rate',
            'term',
            'payment_frequency'
        ];

        foreach ($optional_attributes as $optional_attribute) {
            if (isset($optional_data[$optional_attribute])) {
                $response[$optional_attribute] = $optional_data[$optional_attribute];
            }
        }

        foreach ($mapper as $attribute_mapper) {
            if (!empty($payload[$attribute_mapper['payload_attribute']])) {
                $response[$attribute_mapper['inventory_attribute']] = $this->formatValue($payload[$attribute_mapper['payload_attribute']], $attribute_mapper['type']);
            }
        }

        $response['make'] = !empty($payload['Make']) ? $zop_mapper->exactMatch('makes', $payload['Make'], $payload['Make']) : '';
        $response['stock_no'] = !empty($payload['StockNumber']) ? $payload['StockNumber'] : '';

        if (empty($response['stock_no'])) {
            $response['stock_no'] = $payload['VIN'];
        }

        $response['status'] = !empty($payload['Status']) ? $zop_mapper->exactMatch('statuses', $payload['Status'], "Instock") :
            "Instock";

        $response['vehicle_type'] = !empty($payload['Status']) ? $zop_mapper->exactMatch('vehicle_types', $payload['Status'], "Used") :
            "Used";
        $response['body_type'] = !empty($payload['VehicleType']) ? $zop_mapper->exactMatch('body_types', $payload['VehicleType'], "Other/Don’t Know") : "Other/Don’t Know";

        if (isset($payload['ExteriorColor']['BaseColor'])) {
            $response['exterior_color'] = $payload['ExteriorColor']['BaseColor'];
        }

        if (isset($payload['InteriorColor']['BaseColor'])) {
            $response['interior_color'] = $payload['InteriorColor']['BaseColor'];
        }

        $response['litres'] = !empty($payload['Engine']) ? $this->formatValue($payload['Engine'], 'initial_decimal') : '';
        $response['engine'] = !empty($payload['Cylinders']) ? $zop_mapper->exactMatch('cylinders', $payload['Cylinders'], "Other") :
            "Other";

        if ($payload['Body'] === "CVT" || $payload['Body'] === "eCVT") {
            $response['transmission'] = "CVT";
        } else if (stripos($payload['Transmission'], "Automatic") !== false) {
            $response['transmission'] = "Automatic";
        } else if (stripos($payload['Transmission'], "Continuously Variable") !== false) {
            $response['transmission'] = "CVT";
        } else if ($payload['Transmission'] === "CVT") {
            $response['transmission'] = "CVT";
        }

        $response['drivetrain'] = !empty($payload['DriveWheel']) ? $zop_mapper->exactMatch('drivetrains', $payload['DriveWheel'], "Other/Don’t Know") :
            "Other/Don’t Know";

        if (!empty($payload["DateReceived"])) {
            try {
                $date_received_object = Carbon::parse($payload["DateReceived"]);
            } catch (\Exception $ex) {
                $date_received_object = Carbon::now();
            }
        }

        $response['stock_date'] = $date_received_object->format('Y-m-d');
        $response['passengers'] = !empty($payload['SeatingCapacity']) ? $zop_mapper->exactMatch('passengers', $payload['SeatingCapacity'], "Other") :
            "Other";

        $response['sub_status'] = !empty($payload['Status']) ? $zop_mapper->exactMatch('sub_statuses', $payload['Status'], $response['sub_status']) :
            $response['sub_status'];

        $response["km_miles"] = "KM";

        return $response;
    }

    private function formatValue($value, $data_type)
    {
        switch ($data_type) {
            case 'numeric':
                return preg_replace('/[^0-9]/', '', $value);
                break;

            case 'km_or_miles':
                return strtolower($value) === "miles" ? "Miles" : "KM";
                break;

            case 'decimal':
                return preg_filter('/[^\d.]|(?<!\d)\./', '', $value);
                break;

            case 'alpha_numeric_punct':
                $value = preg_replace("/[^A-Za-z0-9 -]/", "", $value);
                return trim($value);
                break;

            case 'initial_decimal':
                if (preg_match('/^\d+(\.\d+)?/', $value, $matches)) {
                    return $matches[0];
                } else {
                    return '';
                }

                break;
            default:
                return $value;
                break;
        }
    }

    private function saveFileOnCDN($file, $file_path): void
    {
        CloudStorageFactory::getInstance()->save($file, $file_path);
    }

    private function generateFilePath(): string
    {
        return  $this->getLocalWritableFolderPath() . "/" . Utility::uniqueNumberBasedString("PBS", 0) . ".txt";
    }

    private function getLocalWritableFolderPath(): string
    {
        return WRITEPATH . API_IMPORT_FOLDER . "/" . md5(db_connect()->getDatabase());
    }
}
