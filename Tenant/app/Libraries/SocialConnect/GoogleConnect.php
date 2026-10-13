<?php

namespace Tenant\app\Libraries\SocialConnect;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use Google\Client;
use Tenant\app\Helpers\SocialUtility;
use NumberFormatter;
use Tenant\app\Libraries\SocialConnect\SocialConnectAbstract;
use Tenant\app\Models\SocialConnectListModel;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Repositories\SocialConnectRepository;
use GuzzleHttp\Psr7\Request;


/**
 * GoogleConnect   This class is a wrapper for Google API To post data
 */
class GoogleConnect extends SocialConnectAbstract
{
    private const SOCIALCONNECT_TEMPLATE = 'socialconnect_template';
    private const BUSINESS_WEBSITE = 'business_website';
    /**
     * @var string
     */
    private const INVENTORY_TYPE = 'inventory_type';
    /**
     * @var string
     */
    private const WORDPRESS_TYPE = 'wordpress_type';
    private const MAX_IMAGE_NO = 1;
    private const SOCIAL_GOOGLE = 'socialconnect_google_account';
    private $google;
    private $configuration;
    private $repository;

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->configuration = new Configuration();
        $this->repository = new SocialConnectRepository();
        $this->google = new Client();
        $this->google->setAuthConfig(ROOTPATH . 'ggl_secret.json');
        $this->google->addScope('https://www.googleapis.com/auth/plus.business.manage');
        $this->google->setState(SocialUtility::getDbNameForState());
        $this->google->setRedirectUri(SocialUtility::getValueFromEnv('socialconnect.google.callback.url'));
        $this->google->setAccessType('offline'); // offline access
        $this->google->setApprovalPrompt('force'); //consent
        $this->google->setIncludeGrantedScopes(true); // incremental auth
    }

    public function revoke(array $options = []): array
    {
        $access_token_object = $this->getAccessTokenFromConfig();
        $this->google->setAccessToken($access_token_object);

        $this->google->revokeToken();
        $data = [
            'socialconnect_google_account' => '',
            'socialconnect_google_refresh_token' => '',
            'socialconnect_google_connect' => '',
            'default_socialconnect_google_location' => ''
        ];

        $this->configuration->setConfiguration($data);
        return ["success" => "Token has been revoked. Please reconnect again."];
    }

    private function getAccessTokenFromConfig(): ?array
    {
        $token['access_token'] = "";
        $accessToken = $this->configuration->findConfigurationColumnsByKey('socialconnect_google_connect');
        return json_decode($accessToken->value, true);
    }

    public function saveConfigurations(array $data = []): void
    {
        $this->configuration->setConfiguration($data);
    }

    public function postToSocialById(string $id)
    {

        $response = [];
        try {
            $accessToken = $this->getAccessTokenFromConfig();

            if ($accessToken['access_token'] == "") {
                throw new \Exception($this->errors['ACCOUNT_UNLINKED']);
            }

            // IMPORTANT: load saved token into Google Client
            $this->google->setAccessToken($accessToken);

            // Refresh if expired
            if ($this->google->isAccessTokenExpired()) {

                $refreshToken = $this->getRefreshToken();

                if (empty($refreshToken)) {
                    throw new \Exception(
                        'Google access token expired and no refresh token is available.'
                    );
                }

                $newToken = $this->google->fetchAccessTokenWithRefreshToken(
                    $refreshToken
                );

                if (isset($newToken['error'])) {
                    throw new \Exception(
                        'Unable to refresh Google token: '
                        . ($newToken['error_description'] ?? $newToken['error'])
                    );
                }

                $newToken['refresh_token'] = $refreshToken;

                $this->google->setAccessToken($newToken);

                // Save refreshed token
                $this->configuration->setConfiguration([
                    'socialconnect_google_connect' => json_encode($newToken)
                ]);
            }

            $invLine = (new SocialConnectListModel())->findOneByInventoryId($id);
            if (empty($invLine)) {
                throw new \Exception('Inventory not found');
            }

            $invLine = $this->preparePostData($invLine);
            $special = $this->getTemplateConfiguration();
            $template = $this->renderTemplate($special, $invLine);
            if (empty($template)) {
                throw new \Exception('Failed to generate template');
            }

            $images = $this->getImages($invLine);

            $gpage = $this->getGooglePage();
            $gpage['vdpurl'] = $this->getVdpUrl($invLine)  ?? null ;


            if (empty($gpage)) {
                throw new \Exception('No Google page configured with Google MyBussiness');
            }

            $graphNode = $this->postToSocialMedia($template, $images, $gpage);

            if (isset($graphNode['error'])) {
                throw new \Exception('Error posting to Google :   ' . $graphNode['error']['message'] ?? 'Unknown error');
            }

            return $this->updateSocialStatus($invLine['stock_no'], 'success');

        } catch (\Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return ['error' => $e->getMessage()];
        }
    }

    private function getRefreshToken(): string
    {
        $refresh_token = $this->configuration->findConfigurationColumnsByKey('socialconnect_google_refresh_token');
        return $refresh_token->value;
    }

    /**
     * Prepare the post data.
     *
     * @param array $invLine
     * @return array
     */
    protected function preparePostData(array $invLine): array
    {
        $frmtOdo = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
        $fmtPrice = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $price =  ($invLine['special_price_on'] == "1" )? $invLine['special_price'] : $invLine['price'];


        $invLine['odometer'] = $frmtOdo->format($invLine['odometer']);
        $invLine['price'] = $fmtPrice->formatCurrency($price, "USD");
        $invLine['vdpurl'] = $this->getVdpUrl($invLine);
        $invLine['siteurl'] = $this->getSiteUrl();

        $description = SyndicationUtility::parseDescription($invLine['vehicle_description'], 4995);
        $invLine['vehicle_description'] = strlen($description) > 5000
            ? substr($description, 0, 5000)
            : $description;

        return $invLine;
    }

    /**
     * Generates the Vehicle Details Page (VDP) URL based on configuration and inventory data.
     *
     * @param mixed $inv The inventory data used for URL creation.
     * @return string A string representing the generated VDP URL.
     */
    public function getVdpUrl($inv): string
    {
        $bWebsite = $this->getConfigurationValue(self::BUSINESS_WEBSITE);
        $iType = $this->getConfigurationValue(self::INVENTORY_TYPE);
        $wordPressType = $this->getConfigurationValue(self::WORDPRESS_TYPE);
        return Utility::urlCreation($bWebsite, $iType, $inv, $wordPressType);
    }

    /**
     * Get the configuration value.
     *
     * @param string $key
     * @return string|null
     */
    private function getConfigurationValue(string $key): ?string
    {
        $result = (new Configuration())->findConfigurationColumnsByKey($key);
        return $result->value ?? null;
    }

    public function getSiteUrl(): string
    {
        return $this->getConfigurationValue(self::BUSINESS_WEBSITE);

    }

    /**
     * Get the template configuration.
     *
     * @return string
     */
    private function getTemplateConfiguration(): string
    {
        static $templateConfig = null;

        if ($templateConfig === null) {
            $configuration = new Configuration();
            $templateConfig = $configuration->findConfigurationColumnsByKey(self::SOCIALCONNECT_TEMPLATE, ['value'])->value ?? 'default';
        }

        return $templateConfig;
    }

    /**
     * Render the template.
     *
     * @param string $special
     * @param array $invLine
     * @return string
     */
    private function renderTemplate(string $special, array $invLine): string
    {
        $templatePath = APPPATH . 'Views/socialconnect/' . $special . '.php';

        if (!is_file($templatePath)) {
            $special = 'default';
        }

        return view('socialconnect/' . $special, ['invLine' => $invLine]);
    }

    /**
     * Get the images from the inventory array.
     *
     * This method extracts image URLs from the inventory array and returns them as an array.
     * It supports two types of image URL fields: 'image_with_overlay_urls' and 'image_urls'.
     * If both fields are empty, an empty array is returned.
     * The method limits the number of images to a maximum defined by the MAX_IMAGE_NO constant.
     *
     * @param array $inv The inventory array containing image URLs.
     * @return array|null An array of image URLs or an empty array if no images are found.
     */
    private function getImages(array $inv): ?array
    {
        $imageString = $inv['image_with_overlay_urls'] ?? $inv['image_urls'] ?? '';
        if (empty($imageString)) {
            return [];
        }

        // Limit to first 5 images since that's the Facebook limit
        $images = array_slice(explode(";", $imageString), 0, self::MAX_IMAGE_NO);
        return array_map(fn($image) => trim(SyndicationUtility::getImageUrl($image,SyndicationUtility::getOptimizeCdnUrl())), $images);
    }

    public function getGooglePage(): array
    {
        $records = [];
        $records = $this->getSocialConfiguration(['business_location_id', 'parent_account_id']);
        return $records;
    }

    /**
     * Retrieves the social configuration values from the configuration storage.
     *
     * @param array $fields An array of specific fields to retrieve. If empty, all available fields will be returned.
     * @return array An associative array containing the social configuration data. Returns an empty array if no data is found.
     */
    public function getSocialConfiguration($fields = []): array
    {
        $records = [];
        $configuration = new Configuration();
        $googleData = $configuration->findConfigurationColumnsByKey(self::SOCIAL_GOOGLE);

        if ($googleData->value == "") {
            return $records;
        }

        $default_location = $configuration->findConfigurationColumnsByKey('default_socialconnect_google_location');
        if ($default_location->value == "") {
            return $records;
        }


        $page = json_decode($googleData->value);

        if (count($fields) > 0) {

            foreach ($page as $key => $field) {
                if ($default_location->value == $field->business_location_id) {
                    $records['business_location_id'] = $field->business_location_id;
                    $records['parent_account_id'] = $field->parent_account_id;
                }
            }
        }

        return $records;
    }

    public function postToSocialMedia(?string $message = null, array $imagePaths = [], array $extra = []): ?array
    {
        $accountId = $extra['parent_account_id'] ?? null;
        $locationId = $extra['business_location_id'] ?? null;
        $targetUrl = $extra['vdpurl'] ?? null;

        if (empty($accountId) || empty($locationId)) {
            return ['error' => 'Invalid page token or Google page ID'];
        }


        try {
            $response =  $this->createGooglePost($accountId, $locationId,$message, $imagePaths, $targetUrl) ;

            return $response;
        } catch (\Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return ["error" => $e->getMessage()];
        }

    }

    public function createGooglePost(
        string $accountId,
        string $locationId,
        string $message,
        array $imagePaths = [],
        ?string $targetUrl = null
    ) {
        $http = $this->google->authorize();

        $url = 'https://mybusiness.googleapis.com/v4/'
             . $accountId
            . '/'. $locationId
            . '/localPosts';

        $media = [];

        foreach ($imagePaths as $imageUrl) {
            $media[] = [
                'mediaFormat' => 'PHOTO',
                'sourceUrl'   => $imageUrl,
            ];
        }

        $data = [
            'languageCode' => 'en-US',
            'summary'      => $message,
            'topicType'    => 'STANDARD',
        ];

        if (!empty($media)) {
            $data['media'] = $media;
        }

        if (!empty($targetUrl)) {
            $data['callToAction'] = [
                'actionType' => 'LEARN_MORE',
                'url'        => $targetUrl,
            ];
        }



        $request = new Request(
            'POST',
            $url,
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($data)
        );

        try {
            $response = $http->send($request);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $error = [
                'success' => false,
                'message' => $e->getMessage(),
            ];

            if ($e->hasResponse()) {
                $error['response'] = json_decode(
                    $e->getResponse()->getBody()->getContents(),
                    true
                );
            }

            return $error;
        }
    }

    public function authorize(array $data = []): ?array
    {
        if (empty($data)) {
            return ['url' => $this->google->createAuthUrl()];
        } else {
            try {
                extract($data);

                if ((isset($code) === false) || (isset($state) == false)) {
                    throw new \Google\Exception('Missing Code. Invalid Request');
                }

                $token = $this->google->fetchAccessTokenWithAuthCode($code);

                if (!empty($token)) {

                    $response = [];

                    $database_name = base64_decode($state);
                    $db = \Config\Database::connect();
                    $db->setDatabase($database_name);

                    $this->google->setAccessToken($token);
                    $response['socialconnect_google_connect'] = json_encode($token);

                    $parent_account_id = $this->getParentAccountID($this->google);

                    $locations = $this->getBussinessLocations($this->google, $parent_account_id);

                    $response['socialconnect_google_account'] = json_encode($locations);

                    if ($this->google->getRefreshToken() != null) {
                        $response['socialconnect_google_refresh_token'] = $this->google->getRefreshToken();
                    }

                    $response['success'] = 'Google Business Connect Integrated. You can close the window.';
                    return $response;
                } else {
                    throw new \Google\Exception('Invalid Token.');
                }
            } catch (\Exception $ex) {
                return ["errors" => $ex->getMessage()];
            }
        }

        return [];
    }

    /**
     * Return Get Parent Account ID
     *
     * @return string
     */
    private function getParentAccountID($account): ?string
    {
        $businessAccount = new \Google\Service\MyBusinessAccountManagement($account);
        $listAccounts = $businessAccount->accounts->listAccounts()->getAccounts();

        foreach ($listAccounts as $key => $val) {
            return $val->name;
        }
        return null;
    }

    /**
     * Return business locations details
     *
     * @return array
     */
    private function getBussinessLocations($locations, $parentAccountID): array
    {
        $businessInformation = new \Google\Service\MyBusinessBusinessInformation($locations);
        $mask = ['readMask' => 'storeCode,name,languageCode,title,storefrontAddress,websiteUri,serviceArea,labels,openInfo,metadata,profile,relationshipData'];
        $list_locations_responsex = $businessInformation->accounts_locations->listAccountsLocations($parentAccountID, $mask);
        $list_locations = $list_locations_responsex->getLocations();
        $business_locations = [];

        foreach ($list_locations as $key => $val) {
            $address = $val->getStorefrontAddress()->addressLines[0] . ',' . $val->getStorefrontAddress()->locality . ',' . $val->getStorefrontAddress()->administrativeArea . ',' . $val->getStorefrontAddress()->postalCode . ',' . $val->getStorefrontAddress()->regionCode;
            $business_locations[] = [
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

        return $business_locations;
    }

    /**
     * Update the social status in the database.
     *
     * @param string $stockNo
     * @param string $graphNode
     * @return array
     */
    private function updateSocialStatus(string $stockNo, string $graphNode): array
    {
        $data = [
            'stock_no' => $stockNo,
            'google_status' => $graphNode,
        ];

        $gModel = new SocialConnectRepository();
        $gdata = $gModel->createOrUpdate($data);

        return isset($gdata['success'])
            ? ['success' => 'Google post has been added successfully']
            : ['error' => 'Failed to update Google post'];
    }

    public function getAccessTokenFromDb(): ?string
    {
        // TODO: Implement getAccessTokenFromDb() method.
    }

    public function getLoginUrl(): string
    {
        // TODO: Implement getLoginUrl() method.
    }

    public function unlinkAccount(): array
    {
        // TODO: Implement unlinkAccount() method.
    }

    private function save($reviews)
    {
        foreach ($reviews as $review) {
            $result = $this->repository->createOrUpdate($review);
        }
    }

    private function getBusinessAccountsList(): array
    {
        try {
            $locations = $this->configuration->findConfigurationColumnsByKey('socialconnect_google_account');
            return json_decode($locations->value, true);
        } catch (\Exception $ex) {
            return [];
        }
    }

    private function getDefaultGoogleLocation(): string
    {
        $default_google_location_object = $this->configuration->findConfigurationColumnsByKey('default_socialconnect_google_location');
        return $default_google_location_object->value;
    }

    private function refreshToken()
    {
        $current_refresh_token = $this->getRefreshToken();
        $this->google->fetchAccessTokenWithRefreshToken($current_refresh_token);
        $updated_access_token = $this->google->getAccessToken();
        $updated_access_token['refresh_token'] = $current_refresh_token;

        $this->google->setAccessToken($updated_access_token);
        $this->configuration->setConfiguration(['socialconnect_google_connect' => json_encode($updated_access_token)]);
        $this->configuration->setConfiguration(['socialconnect_google_refresh_token' => $updated_access_token['refresh_token']]);
    }
}
