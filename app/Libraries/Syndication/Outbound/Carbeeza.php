<?php

namespace App\Libraries\Syndication\Outbound;

use App\Helpers\SyndicationUtility;
use App\Libraries\Syndication\SyndicationAbstract;
use App\Repositories\SyndicationRepository;
use Hug\Sftp\Sftp as Sftp;
use Tenant\app\Repositories\LocationRepository;

class Carbeeza extends SyndicationAbstract
{
    protected $imageUrlLink;
    protected $today;

    public function __construct($dataconfig)
    {
        $this->config = $dataconfig;
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($this->config->db_name);
    }

    public function setHeader()
    {
        return $this->header = [
            'FEED_UUID',
            'ORG_UUID',
            'PROVIDER_UUID',
            'PROVIDER_IMPORT_ID',
            'PROVIDER_NAME',
            'OP_TYPE',
            'EXTRACT_DATE',
            'VIN',
            'IMPORT_ID',
            'STOCK_CODE',
            'ODOMETER',
            'ODOMETER_UNITS',
            'YEAR',
            'MAKE',
            'MODEL',
            'MODEL_MFR',
            'TRIM',
            'STYLE',
            'STYLE_CHROME',
            'VEHICLE_SUBTYPE',
            'EXTERNAL_COLOUR',
            'EXTERNAL_COLOUR_GEN',
            'EXTERNAL_COLOUR_MFR',
            'EXTERNAL_COLOUR_CHROME',
            'EXTERNAL_COLOUR_RGB',
            'INTERNAL_COLOUR',
            'INTERNAL_COLOUR_GEN',
            'INTERNAL_COLOUR_MFR',
            'INTERNAL_COLOUR_CHROME',
            'INTERNAL_COLOUR_RGB',
            'DOORS',
            'SEATS',
            'TRANSMISSION',
            'CYLINDERS',
            'ENGINE_CAPACITY',
            'DRIVE_TRAIN',
            'FUEL',
            'ENGINE_TYPE',
            'FORCED_INDUCTION',
            'NET_TORQUE',
            'HORSEPOWER',
            'FUEL_ECON_HIGHWAY',
            'FUEL_ECON_CITY',
            'FUEL_CAPACITY',
            'BRANDING',
            'LOCATION_LAT',
            'LOCATION_LONG',
            'LOCATION_DATUM',
            'GOOGLE_PLACE_ID',
            'COMMENTS_EN',
            'COMMENTS_FR',
            'COMMENTS_ES',
            'COMMENTS_UNK',
            'COUNTRY',
            'COUNTRY_ISO',
            'LANGUAGE_CODE',
            'FEATURES_STD',
            'FEATURES_STD_CHROME',
            'FEATURES_STD_CHROME_UNORDERED',
            'FEATURES_STD_MFR',
            'FEATURES_OPT',
            'FEATURES_OPT_CHROME',
            'FEATURES_OPT_CHROME_UNORDERED',
            'FEATURES_OPT_MFR',
            'REGISTERED_TERRITORY',
            'CURRENCY',
            'INVENTORY_DATE',
            'PURCHASE_DATE',
            'SOLD_DATE',
            'ACQUISITION_COST',
            'INCENTIVE_ELIGIBLE',
            'EMBEDDED_INCENTIVE_DISCOUNT',
            'CUSTOMER_INCENTIVES',
            'REF_VALUE_WHOLE_ACQ',
            'REPAIR_COMPLETED_AMT',
            'REPAIR_COMPLETED_COST',
            'DAMAGE_MIN_OUTSTANDING_AMT',
            'DAMAGE_MIN_OUTSTANDING_COST',
            'BASE_PROFIT',
            'MSRP',
            'UNFIN_PRE_FEE_PRICE',
            'INCLUDED_PRODUCT_AMT',
            'INCLUDED_PRODUCT_COST',
            'RECON_AMT',
            'RECON_COST',
            'FEES_MIN',
            'CASH_PRICE_EXCL_FEES_INCENT',
            'PRICE_1',
            'PRICE_2',
            'PRICE_3',
            'PRICE_4',
            'PRICE_5',
            'PRICE_6',
            'PRICE_7',
            'PRICE_8',
            'PRICE_9',
            'PRICE_10',
            'IMAGE_URLS',
            'ADDRESS_PB_COUNTRY',
            'ADDRESS_PB_COUNTRY_ISO',
            'ADDRESS_PB_TERRITORY',
            'ADDRESS_PB_TERRITORY_ISO',
            'ADDRESS_PB_CITY',
            'ADDRESS_PB_POSTAL_CODE',
            'ADDRESS_PB_LINE1',
            'ADDRESS_PB_LINE2',
            'PHONE_1_E164',
            'PHONE_1_EXT',
            'PHONE_1_TYPE',
            'PHONE_2_E164',
            'PHONE_2_EXT',
            'PHONE_2_TYPE',
            'EMAIL_1',
            'EMAIL_2',
            'EMAIL_3',
            'PROVIDER_LOCATION_LAT',
            'PROVIDER_LOCATION_LONG',
            'PROVIDER_LOCATION_DATUM',
            'PROVIDER_GOOGLE_PLACE_ID',
            'ORG_NAME',
            'ORG_IMPORT_ID',
            'MAPPING_1',
            'MAPPING_2',
            'MAPPING_3',
            'MAPPING_4',
            'MAPPING_5',
        ];
    }

    public function formatImages($images): string
    {
        $p = 0;
        $imageUrl = null;
        $photos = explode(";", $images);
        foreach ($photos as $pkey => $photo) {
            if ($p != 0) {
                $imageUrl .= "|";
            }

            $imageUrl .= SyndicationUtility::getImageUrl($photo);
            $p++;
        }

        return $imageUrl;
    }

    public function generateFile()
    {

        $syndication = [];
        $records = (new SyndicationRepository())->inventoryListBySyndication($this->config->source);
        $this->setHeader();
        $this->today = date("Ymd-Hi");
        $this->config->icount = count($records);

        foreach ($records as $key => $line) {

            // find video links from file attributes
            $line['video'] = $video = '';
            if ($line['files'] != null) {
                $video = explode(';', $line['files']);
                foreach ($video as $video_data) {
                    $video_data = explode('          ', $video_data);
                    if ($video_data[0] == "Video Url") // static label name , will effect if changed in future
                    {
                        $line['video'] = $video_data[1];
                    }

                }
            }

            if (!empty($line['image_with_overlay_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_with_overlay_urls']);
            } elseif (!empty($line['image_urls'])) {
                $this->imageUrlLink = $this->formatImages($line['image_urls']);
            } else {
                $this->imageUrlLink = "";
            }

            $location = gettype($line['location']) == 'string' ? $line['location'] : "";
            $this->getBusinessLocation($location);

            $syndication[] = $this->formatLineRecord($line);
        }

        $directory = SYNDICATION_EXPORT . $this->config->client_id;
        $this->config->file_name = $this->today . "_" . $this->config->identity_id . ".csv";

        $csvfile = $directory . '/' . $this->config->file_name;

        // directory creation
        (is_dir($directory)) ?: mkdir($directory, 0777);

        //File creation
        $file = new \CodeIgniter\Files\File($csvfile);
        $csv = $file->openFile('w');
        $csv->fputcsv($this->header);

        foreach ($syndication as $key => $line) {
            $csv->fputcsv($line);
        }
        return true;
    }

    public function formatLineRecord($line)
    {
        $comments = (new SyndicationRepository())->getInventoryDescParse($line);

        $data['FEED_UUID'] = "";
        $data['ORG_UUID'] = "";
        $data['PROVIDER_UUID'] = $this->validate($this->config->identity_id);
        $data['PROVIDER_IMPORT_ID'] = $this->config->client_id;
        $data['PROVIDER_NAME'] = $this->validate($this->config->business_name);
        $data['OP_TYPE'] = "";
        $data['EXTRACT_DATE'] = $this->today;
        $data['VIN'] = $this->validate($line['vin']);
        $data['IMPORT_ID'] = $this->validate($line['vin']);
        $data['STOCK_CODE'] = $this->validate($line['stock_no']);
        $data['ODOMETER'] = $this->validate($line['odometer']);
        $data['ODOMETER_UNITS'] = $this->validate($line['km_miles']);
        $data['YEAR'] = $this->validate($line['year']);
        $data['MAKE'] = $this->validate($line['make']);
        $data['MODEL'] = $this->validate($line['model']);
        $data['MODEL_MFR'] = "";
        $data['TRIM'] = $this->validate($line['trim']);
        $data['STYLE'] = "";
        $data['STYLE_CHROME'] = "";
        $data['VEHICLE_SUBTYPE'] = $this->validate($line['body_type']);
        $data['EXTERNAL_COLOUR'] = $this->validate($line['exterior_color']);
        $data['EXTERNAL_COLOUR_GEN'] = "";
        $data['EXTERNAL_COLOUR_MFR'] = "";
        $data['EXTERNAL_COLOUR_CHROME'] = "";
        $data['EXTERNAL_COLOUR_RGB'] = "";
        $data['INTERNAL_COLOUR'] = $this->validate($line['interior_color']);
        $data['INTERNAL_COLOUR_GEN'] = "";
        $data['INTERNAL_COLOUR_MFR'] = "";
        $data['INTERNAL_COLOUR_CHROME'] = "";
        $data['INTERNAL_COLOUR_RGB'] = "";
        $data['DOORS'] = $this->validate($line['door']);
        $data['SEATS'] = $this->validate((int) filter_var($line['passengers'], FILTER_SANITIZE_NUMBER_INT));
        $data['TRANSMISSION'] = $this->validate($line['transmission']);
        $data['CYLINDERS'] = "";
        $data['ENGINE_CAPACITY'] = $this->validate($line['litres']);
        $data['DRIVE_TRAIN'] = $this->validate($line['drivetrain']);
        $data['FUEL'] = $this->validate($line['fuel_type']);
        $data['ENGINE_TYPE'] = $this->validate($line['engine']);
        $data['FORCED_INDUCTION'] = "";
        $data['NET_TORQUE'] = "";
        $data['HORSEPOWER'] = "";
        $data['FUEL_ECON_HIGHWAY'] = $this->validate($line['fuel_hwy']);
        $data['FUEL_ECON_CITY'] = $this->validate($line['fuel_city']);
        $data['FUEL_CAPACITY'] = "";
        $data['BRANDING'] = $this->validate($line['vehicle_type']);
        $data['LOCATION_LAT'] = "";
        $data['LOCATION_LONG'] = "";
        $data['LOCATION_DATUM'] = "";
        $data['GOOGLE_PLACE_ID'] = "";
        $data['COMMENTS_EN'] = SyndicationUtility::parseComment($comments);
        $data['COMMENTS_FR'] = "";
        $data['COMMENTS_ES'] = "";
        $data['COMMENTS_UNK'] = "";
        $data['COUNTRY'] = "";
        $data['COUNTRY_ISO'] = "";
        $data['LANGUAGE_CODE'] = "";
        $data['FEATURES_STD'] = "";
        $data['FEATURES_STD_CHROME'] = "";
        $data['FEATURES_STD_CHROME_UNORDERED'] = "";
        $data['FEATURES_STD_MFR'] = "";
        $data['FEATURES_OPT'] = "";
        $data['FEATURES_OPT_CHROME'] = "";
        $data['FEATURES_OPT_CHROME_UNORDERED'] = "";
        $data['FEATURES_OPT_MFR'] = "";
        $data['REGISTERED_TERRITORY'] = "";
        $data['CURRENCY'] = 'CAD'; // Static Value
        $data['INVENTORY_DATE'] = "";
        $data['PURCHASE_DATE'] = "";
        $data['SOLD_DATE'] = "";
        $data['ACQUISITION_COST'] = "";
        $data['INCENTIVE_ELIGIBLE'] = "";
        $data['EMBEDDED_INCENTIVE_DISCOUNT'] = "";
        $data['CUSTOMER_INCENTIVES'] = "";
        $data['REF_VALUE_WHOLE_ACQ'] = "";
        $data['REPAIR_COMPLETED_AMT'] = "";
        $data['REPAIR_COMPLETED_COST'] = "";
        $data['DAMAGE_MIN_OUTSTANDING_AMT'] = "";
        $data['DAMAGE_MIN_OUTSTANDING_COST'] = "";
        $data['BASE_PROFIT'] = "";
        $data['MSRP'] = "";
        $data['UNFIN_PRE_FEE_PRICE'] = "";
        $data['INCLUDED_PRODUCT_AMT'] = "";
        $data['INCLUDED_PRODUCT_COST'] = "";
        $data['RECON_AMT'] = "";
        $data['RECON_COST'] = "";
        $data['FEES_MIN'] = "";
        $data['CASH_PRICE_EXCL_FEES_INCENT'] = ($line['special_price_on'] == "1") ? $line['special_price'] : $line['price'];
        $data['PRICE_1'] = "";
        $data['PRICE_2'] = "";
        $data['PRICE_3'] = "";
        $data['PRICE_4'] = "";
        $data['PRICE_5'] = "";
        $data['PRICE_6'] = "";
        $data['PRICE_7'] = "";
        $data['PRICE_8'] = "";
        $data['PRICE_9'] = "";
        $data['PRICE_10'] = "";
        $data['IMAGE_URLS'] = $this->imageUrlLink;
        $data['ADDRESS_PB_COUNTRY'] = $this->validate($this->config->business_country);
        $data['ADDRESS_PB_COUNTRY_ISO'] = "";
        $data['ADDRESS_PB_TERRITORY'] = $this->validate($this->config->business_state);
        $data['ADDRESS_PB_TERRITORY_ISO'] = "";
        $data['ADDRESS_PB_CITY'] = $this->validate($this->config->business_city);
        $data['ADDRESS_PB_POSTAL_CODE'] = $this->validate($this->config->business_code);
        $data['ADDRESS_PB_LINE1'] = $this->validate($this->config->business_address);
        $data['ADDRESS_PB_LINE2'] = "";
        $data['PHONE_1_E164'] = $this->validate($this->config->business_phone);
        $data['PHONE_1_EXT'] = "";
        $data['PHONE_1_TYPE'] = "";
        $data['PHONE_2_E164'] = "";
        $data['PHONE_2_EXT'] = "";
        $data['PHONE_2_TYPE'] = "";
        $data['EMAIL_1'] = $this->validate($this->config->business_email);
        $data['EMAIL_2'] = "";
        $data['EMAIL_3'] = "";
        $data['PROVIDER_LOCATION_LAT'] = "";
        $data['PROVIDER_LOCATION_LONG'] = "";
        $data['PROVIDER_LOCATION_DATUM'] = "";
        $data['PROVIDER_GOOGLE_PLACE_ID'] = "";
        $data['ORG_NAME'] = "";
        $data['ORG_IMPORT_ID'] = "";
        $data['MAPPING_1'] = "";
        $data['MAPPING_2'] = "";
        $data['MAPPING_3'] = "";
        $data['MAPPING_4'] = "";
        $data['MAPPING_5'] = "";

        return $data;
    }

    public function uploadToFtp()
    {
        $csvfile = SYNDICATION_EXPORT . $this->config->client_id . '/' . $this->config->file_name;
        $remoteFile = $this->config->file_name;
        $uploadResult = 0;

        $login = Sftp::test($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $this->config->ftp_port, $timeout = 14);
        if ($login) {
            $uploadResult = Sftp::upload($this->config->ftp_host, $this->config->ftp_user, $this->config->ftp_pass, $csvfile, $remoteFile, $this->config->ftp_port, $timeout = 14);
        }

        return $uploadResult;
    }

    public function validate($data)
    {
        $proData = ($data == 'null') ? "" : $data;
        return $proData;
    }

    public function archive(): string
    {
        return $this->config->file_name;
    }

    public function getBusinessLocation($location_name)
    {
        $business = (new LocationRepository())->getLocationByName($location_name);
        if ($business) {
            $this->config->business_tname = $business['name'];
            $this->config->business_name = $business['business_name'];
            $this->config->business_address = $business['street'];
            $this->config->business_state = $business['state'];
            $this->config->business_city = $business['city'];
            $this->config->business_code = $business['zipcode'];
            $this->config->business_country = $business['country'];
            $this->config->business_phone = $business['phone'];
            $this->config->business_email = $business['email'];
        } else {
            // throws error  @TODO catch/handle no data error
        }
    }

}