<?php

namespace App\Libraries\Syndication\Outbound;

use App\Repositories\SyndicationRepository;
use App\Libraries\Syndication\SyndicationAbstract;
use Tenant\app\Repositories\DealRepository;
use Carbon\Carbon;

class Siriusxm extends SyndicationAbstract
{
    // Constants for record types
    const RECORD_TYPE_INVENTORY = 'inv';
    const RECORD_TYPE_SOLD = 'sold';
    const RECORD_TYPE_CANCEL = 'cancel';

    // Constants for event names
    const EVENT_VEHICLE_RECEIVED = "VehicleReceived";
    const EVENT_VEHICLE_SOLD = "VehicleSold";
    const EVENT_VEHICLE_SALE_CANCELLED = "VehicleSaleCancelled";

    protected $imageUrlLink;
    protected $recordType;

    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        switch ($this->recordType) {
            case self::RECORD_TYPE_INVENTORY:
                $this->header = ['Event Name', 'Event Date', 'Dealer ID', 'VIN', 'Make', 'Model', 'Model year', 'Stock number', 'Odometer'];
                break;
            case self::RECORD_TYPE_SOLD:
                $this->header = ['Event Name', 'Event Date', 'Dealer ID', 'VIN', 'Make', 'Model', 'Model year', 'Stock number', 'Odometer', 'First Name', 'Middle Name', 'Last Name', 'Title', 'AddressLine1', 'AddressLine2', 'AddressLine3', 'City', 'State', 'Country', 'Postal Code', 'Email Address', 'Home phone #', 'Sale Type'];
                break;
            case self::RECORD_TYPE_CANCEL:
                $this->header = ['Event Name', 'Event Date', 'Dealer ID', 'VIN', 'Make', 'Model', 'Model year', 'Stock number'];
                break;
        }
        return $this->header;
    }

    public function formatImages($images): string
    {
        return true;
    }

    public function generateFile()
    {
        $this->getInvt(self::RECORD_TYPE_INVENTORY);
        $this->getInvt(self::RECORD_TYPE_SOLD);
        $this->getInvt(self::RECORD_TYPE_CANCEL);
        return true;
    }

    public function getInvt($recordType)
    {
        $syndication = [];
        $this->recordType = $recordType;

        switch ($this->recordType) {
            case self::RECORD_TYPE_INVENTORY:
                $records = (new SyndicationRepository())->inventoryListBySyndicationYesterday($this->config->source);
                //$records = (new SyndicationRepository())->inventoryListWithoutSyndication($this->config->source);
                break;
            case self::RECORD_TYPE_SOLD:
                $records = (new DealRepository())->dealWithInventoryAndCustomers("Delivered");
                break;
            case self::RECORD_TYPE_CANCEL:
                $records = (new DealRepository())->dealWithInventoryAndCustomers("Cancelled");
                break;
        }

        $this->setHeader();
        $this->config->icount = count($records);

        foreach ($records as $key => $line) {
            $syndication[] = $this->formatLineRecord($line);
        }

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $csvfile = $directory . '/' . $this->recordType . '_' . $this->config->file_name;

        // directory creation, recursive with permissions
        is_dir($directory) or mkdir($directory, 0777);

        //File creation
        $file = new \CodeIgniter\Files\File($csvfile);
        $csv = $file->openFile('w');
        $csv->fputcsv($this->header, ',');

        foreach ($syndication as $key => $line) {
            $csv->fputcsv($line, ',');
        }

        return true;
    }

    public function formatLineRecord($line)
    {
        switch ($this->recordType) {
            case self::RECORD_TYPE_INVENTORY:
                return $this->formatInventoryRecord($line);
            case self::RECORD_TYPE_SOLD:
                return $this->formatSoldRecord($line);
            case self::RECORD_TYPE_CANCEL:
                return $this->formatCancelRecord($line);
        }
    }

    private function formatInventoryRecord($line): array
    {
        return [
            'Event Name' => self::EVENT_VEHICLE_RECEIVED,
            'Event Date' => Carbon::parse($line['created_at'])->format('Y-m-d'),
            'Dealer ID' => $this->validate($this->config->identity_id),
            'VIN' => $this->validate($line['vin']),
            'Make' => $this->validate($line['make']),
            'Model' => $this->validate($line['model']),
            'Model year' => $this->validate($line['year']),
            'Stock number' => $this->validate($line['stock_no']),
            'Odometer' => $this->validate($line['odometer']),
        ];
    }

    private function formatSoldRecord($line): array
    {
        return [
            'Event Name' => self::EVENT_VEHICLE_SOLD,
            'Event Date' => Carbon::parse($line['delivery_date_time'])->format('Y-m-d'),
            'Dealer ID' => $this->validate($this->config->identity_id),
            'VIN' => $this->validate($line['vin']),
            'Make' => $this->validate($line['make']),
            'Model' => $this->validate($line['model']),
            'Model year' => $this->validate($line['year']),
            'Stock number' => $this->validate($line['stock_no']),
            'Odometer' => $this->validate($line['odometer']),
            'First Name' => $this->validate($line['customer_first_name']),
            'Middle Name' => '',
            'Last Name' => $this->validate($line['customer_last_name']),
            'Title' => '',
            'AddressLine1' =>  $this->validate($line['customer_street_address']),
            'AddressLine2' => '',
            'AddressLine3' => '',
            'City' =>  $this->validate($line['customer_city']),
            'State' =>  $this->validate($line['customer_province']),
            'Country' =>  $this->validate($line['customer_country']),
            'Postal Code' =>  $this->validate($line['customer_postal_code']),
            'Email Address' =>  $this->validate($line['customer_email']),
            'Home phone #' =>  $this->validate($line['customer_phone']),
            'Sale Type' =>  $line['retail_or_wholesale_flag'] ? 'Wholesale' : 'Retail',
        ];
    }

    private function formatCancelRecord($line): array
    {
        return [
            'Event Name' => self::EVENT_VEHICLE_SALE_CANCELLED,
            'Event Date' => Carbon::parse($line['cancellation_date'])->format('Y-m-d'),
            'Dealer ID' => $this->validate($this->config->identity_id),
            'VIN' => $this->validate($line['vin']),
            'Make' => $this->validate($line['make']),
            'Model' => $this->validate($line['model']),
            'Model year' => $this->validate($line['year']),
            'Stock number' => $this->validate($line['stock_no']),
        ];
    }

    public function uploadToFtp()
    {
        return true;
    }

    function validate($data): string
    {
        return $data ?? "";
    }

    function archive(): string
    {
        return $this->config->file_name;
    }
}