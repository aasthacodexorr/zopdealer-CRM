<?php

namespace App\Controllers;

use Exception;
use Tenant\app\Helpers\SocialUtility;
use Google\Client as Google;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;

/**
 * Handle callback from Google
 */

class GoogleCallbackController extends BaseController
{
    private $google = null;
    private $account_id = null;
    public function __construct()
    {
        $this->google = new Google();
        $this->google->setAuthConfig(ROOTPATH . 'ggl_secret.json');
        $this->google->addScope('https://www.googleapis.com/auth/business.manage');
        $this->google->setRedirectUri(SocialUtility::getValueFromEnv('google.callback.url'));
    }
    public function getGoogleToken()
    {
        $request = \Config\Services::request();
        $code = $request->getGet('code');
        $state = $request->getGet('state');
        $token = $data = [];

        try {
            if ((isset($code) === false) || (isset($state) == false)) {
                throw new \Google\Exception('Missing Code. Invalid Request');
            }

            $token = $this->google->fetchAccessTokenWithAuthCode($code);

            if (empty($token) === false) {
                // Database updates
                $dbase = null;
                $database = base64_decode($state);
                $dbase = \Config\Database::connect();
                $dbase->setDatabase($database);

                $this->google->setAccessToken($token);
                $data['google_reviews'] = json_encode($token);

                // Get Google MyBusiness Name
                $bName = $this->getBussinessName($this->google);

                // Get Google MyBusiness Locations
                $bLocation = $this->getBussinessLocations($this->google, $this->account_id);
                
                $data['google_reviews_account'] = json_encode(array_merge($bName, $bLocation));

                if ($this->google->getRefreshToken() != null) {
                    $data['google_reviews_refresh_token'] = $this->google->getRefreshToken();
                }

                $configuration = new Configuration();
                $records = $configuration->setConfiguration($data);

                if (isset($records['success'])) {
                    echo "<h1>Successfully Integrated, Please close tab now.</h1>";
                }
            }
        } catch (\Google\Exception $e) {
            // When validation fails or other local issues
            echo 'Google returned an error: ' . $e->getMessage();
            exit;
        }
    }

    /**  
     * Return business name 
     * 
     * @return array
    */
    private function getBussinessName($account): array
    {
        $businessAccount = new \Google\Service\MyBusinessAccountManagement($account);
        $listAccounts = $businessAccount->accounts->listAccounts()->getAccounts();
        
        $accounts = [];

        foreach ($listAccounts as $key => $val) {
            $this->account_id = $val->name;
            $accounts = [
                'business_name' => $val->accountName,
                'business_account_id' => $val->name,
            ];
            //@todo : return first result
            break;
        }
        return $accounts;
    }

    /**  
     * Return business locations 
     * 
     * @return array
    */
    private function getBussinessLocations($locations, $parentAccountID) : array
    {
        $businessInformation = new \Google\Service\MyBusinessBusinessInformation($locations);
        $mask = ['readMask' => 'storeCode,name,languageCode,title,storefrontAddress,websiteUri,serviceArea,labels,openInfo,metadata,profile,relationshipData'];
        $list_locations_responsex = $businessInformation->accounts_locations->listAccountsLocations($parentAccountID, $mask);
        $list_locations = $list_locations_responsex->getLocations();
        $blocations = [];

        foreach ($list_locations as $key => $val) {
            $address = $val->getStorefrontAddress()->addressLines[0] . ', ' . $val->getStorefrontAddress()->locality . ', ' . $val->getStorefrontAddress()->administrativeArea . ', ' . $val->getStorefrontAddress()->postalCode . ', ' . $val->getStorefrontAddress()->regionCode;
            $blocations['business_data'][] = [
                'business_website' => $val->websiteUri,
                'business_location_id' => $val->name,
                'business_name' => $val->title,
                'business_address' => $address,
                'business_map' => $val->getMetadata()->mapsUri,
                'business_review_url' => $val->getMetadata()->newReviewUri,
                'business_place_id' => $val->getMetadata()->placeId,
                'parent_account_id' => $parentAccountID
            ];
        }

        return $blocations;
    }
}