<?php

namespace Tenant\app\Libraries\SocialConnect;


use App\Helpers\Utility;
use App\Helpers\SyndicationUtility;
use Exception;
use NumberFormatter;
use Facebook\Authentication\OAuth2Client;
use Facebook\Exceptions\FacebookResponseException as FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException as FacebookSDKException;
use Facebook\Facebook as Facebook;

use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Models\SocialConnectListModel;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Repositories\SocialConnectRepository;


/**
 * InstagramConnect class handles Facebook-specific operations.
 */
class InstagramConnect extends SocialConnectAbstract
{

    /**
     * @var string
     */
    private const GRAPH_VERSION = 'v22.0';
    /**
     * @var string
     */
    private const SOCIAL_INSTAGRAM = 'social_instagram';
    /**
     * @var string
     */
    private const SOURCE = 'fb';
    private const SOURCEIG = 'ig';
    /**
     * @var array
     */
    private const PERMISSIONS = [
        "instagram_basic",
        "instagram_content_publish",
        "pages_show_list",
        "pages_read_engagement",
    ];
    /**
     * @var string
     */
    private const BUSINESS_WEBSITE = 'business_website';
    /**
     * @var string
     */
    private const INVENTORY_TYPE = 'inventory_type';
    /**
     * @var string
     */
    private const WORDPRESS_TYPE = 'wordpress_type';
    /**
     * @var string
     */
    private const MAX_IMAGE_NO = 8;
    /**
     * @var string
     */
    private const SOCIALCONNECT_TEMPLATE = 'socialconnect_template';
    /**
     * @var string
     */
    protected string $callbackUrl;
    /**
     * @var Facebook
     */
    private Facebook $fb;

    /**
     * Constructor initializes the Facebook SDK and sets the callback URL and permissions.
     *
     * @param string $source The social media platform ('facebook' or 'instagram').
     * @param array $permissions The required permissions for the platform.
     */
    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => SocialUtility::getValueFromEnv(self::SOURCE . '.app.id'),
            'app_secret' => SocialUtility::getValueFromEnv(self::SOURCE . '.app.secret'),
            'default_graph_version' => self::GRAPH_VERSION,
        ]);

        $this->callbackUrl = SocialUtility::getValueFromEnv( self::SOURCEIG.'.callback.url');
    }

    /**
     * Get the Facebook OAuth2 client.
     *
     * @return \Facebook\Authentication\OAuth2Client
     */
    public function getOAuth2Client(): \Facebook\Authentication\OAuth2Client
    {
        return $this->fb->getOAuth2Client();
    }

    /**
     * Set the Facebook access token to the database.
     *
     * @return string|null
     */
    public function setDefaultAccessToken($accessToken)
    {
        return $this->fb->setDefaultAccessToken($accessToken);
    }

    /**
     * @return string
     */
    public function getLoginUrl(): string
    {
        $helper = $this->getRedirectHelper();
        return $helper->getLoginUrl($this->callbackUrl, self::PERMISSIONS);
    }

    /**
     * Get the Facebook redirect login helper.
     *
     * @param string|null $customState
     * @return \Facebook\Helpers\FacebookRedirectLoginHelper
     */
    public function getRedirectHelper($customState = null)
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $persistentDataHandler = $helper->getPersistentDataHandler();
        $persistentDataHandler->set('state', $customState ?? SocialUtility::getDbNameForState());
        return $helper;
    }


    /**
     * Retrieves the database name associated with the current state.
     *
     * @return string The database name for the state.
     */
//    private static function getState()
//    {
//        return SocialUtility::getDbNameForState();
//    }

    /**
     * Unlinks the associated social account by clearing the configuration data.
     *
     * @return array An associative array representing the result of the configuration update.
     *               This typically includes the updated configuration status.
     */
    public function unlinkAccount(): array
    {
        $configuration = new Configuration();
        $data = ['social_instagram' => ""];

        try {
            // Revoke the access token using the Facebook SDK
            $accessToken = $this->getAccessTokenFromDb();
            if ($accessToken) {
                $this->fb->delete('/me/permissions', [], $accessToken);
            }
        } catch (FacebookResponseException|FacebookSDKException|Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
        }

        // Clear the configuration data
        return $configuration->setConfiguration($data);
    }

    /**
     * Retrieves the access token for Instagram from the database configuration.
     *
     * @return string|null The access token if available, or null if no token is found.
     */
    public function getAccessTokenFromDb(): ?string
    {
        $fbtoken = $this->getSocialConfiguration(['fb_usertoken']);
        return $fbtoken['fb_usertoken'] ?? '';
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
        $instagramData = $configuration->findConfigurationColumnsByKey(self::SOCIAL_INSTAGRAM);
        if ($instagramData->value == "") {
            return $records;
        }

        $page = json_decode($instagramData->value);
        if (count($fields) > 0) {
            foreach ($fields as $key => $field) {
                $records[$field] = $page->{$field};
            }
        } else {
            $records = [
                'fb_userid' => $page->fb_userid,
                'fb_usertoken' => $page->fb_usertoken,
                'instagram_id' => $page->instagram_id,
                'business_name' => $page->business_name,
                'instagram_username' => $page->instgram_username
            ];
        }

        return $records;
    }

// Social media posting functions

    public function postToSocialById(string $id)
    {

        $response = [];
        try {
            $accessToken = $this->getAccessTokenFromDb();

            if ($accessToken == "") {
                throw new Exception($this->errors['ACCOUNT_UNLINKED']);
            }

            $invLine = (new SocialConnectListModel())->findOneByInventoryId($id);
            if (empty($invLine)) {
                throw new Exception('Inventory not found');
            }

            $invLine = $this->preparePostData($invLine);
            $special = $this->getTemplateConfiguration();
            $template = $this->renderTemplate($special, $invLine);
            if (empty($template)) {
                throw new Exception('Failed to generate template');
            }

            $images = $this->getImages($invLine);
            $fbpage = $this->getInstagramPage();

            if (empty($fbpage)) {
                throw new Exception('No Instagram page configured with Facebook page');
            }

            $graphNode = $this->postToSocialMedia($template, $images, $fbpage);
            if (isset($graphNode['error'])) {
                throw new Exception('Error posting to Instagram : Token Expired -  ' . $graphNode['error']);
            }

            return $this->updateSocialStatus($invLine['stock_no'], $graphNode['success']);

        } catch (Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return ['error' => $e->getMessage()];
        }
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

    /**
     * Retrieves the site URL from the configuration storage.
     *
     * @return string The site URL as stored in the configuration. Returns an empty string if no URL is found.
     */
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

    public function getInstagramPage(): array
    {
        $records = [];
        $records = $this->getSocialConfiguration(['fb_usertoken', 'instagram_id']);
        return $records;
    }

    /**
     *  Get  instagram data from DB
     *
     * @return array
     */
    public function getInstagramAccount(): array
    {
        $records = [];
        $records = $this->getSocialConfiguration(['business_name', 'instagram_username']);
        return $records;
    }

    /**
     * Post to social media.
     *
     * @param string|null $message
     * @param array $imagePaths
     * @param array $extra
     * @return string|null
     */
    public function postToSocialMedia(?string $message = null, array $imagePaths = [], array $extra = []): ?array
    {
        if (empty($extra['fb_usertoken']) || empty($extra['instagram_id'])) {
            return ['error' => 'Invalid page token or Instagram page ID'];
        }

        // Limit images to Facebook's maximum of 5
        $imagePaths = array_slice($imagePaths, 0, self::MAX_IMAGE_NO);

        if (empty($imagePaths)) {
            return ['error' => 'No images provided'];
        }

        try {
            if (count($imagePaths) > 1) {
                $photoIds = $this->uploadMultipleImages($imagePaths, $extra['instagram_id'], $extra['fb_usertoken'],$message);
            }else{
                $photoIds = $this->uploadSingleImage($imagePaths, $extra['instagram_id'], $extra['fb_usertoken'],$message);
            }

            // Step 2: Create a message with all the uploaded images
            if (empty($photoIds)) {
                return ['error' => 'Failed to upload images'];
            }

            $response =  $this->createFeedPost($photoIds, $extra['instagram_id'], $extra['fb_usertoken'], $message) ;
            return $response;
        } catch (FacebookResponseException | FacebookSDKException | Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return ["error" => $e->getMessage()];
        }


    }

    /**
     * Upload single images to Instagram
     */
    private function uploadSingleImage(array $imagePaths, string $pageId,  string $pageAccessToken, string $message ): ?string
    {
        $photoId = null;
            try {
                $headers = get_headers($imagePaths[0], 1);
                // Check if the HTTP status is 200 OK
                if (strpos($headers[0], '200') === false) {
                    throw new Exception("Image not found: $imagePaths[0]");
                }

                $response = $this->fb->fileToUpload($imagePaths[0]);
                $uploadResponse = $this->fb->post('/' . $pageId . '/media', ['caption' => $message, 'image_url' => $response->getFilePath()], $pageAccessToken);
                $photoId = $uploadResponse->getGraphNode()['id'] ?? null;
            } catch (Exception $e) {
                log_message('warning', 'Error uploading image: ' . $e->getMessage());
            }
        return $photoId ;
    }

    /**
     * Upload multiple images to Facebook
     */
    private function uploadMultipleImages(array $imagePaths, string $pageId, string $pageAccessToken, string $message ): ?string
    {
        $photoIds = [];
        $errors = [];
        $containerId = null;

        foreach ($imagePaths as $index => $imagePath) {
            try {
                $headers = get_headers($imagePath, 1);
                // Check if the HTTP status is 200 OK
                if (strpos($headers[0], '200') === false) {
                    $errors[] = "Image not found: $imagePath";
                    continue;
                }

                $response = $this->fb->fileToUpload($imagePath);
                $uploadResponse = $this->fb->post('/' . $pageId . '/media', ['is_carousel_item' => 'true', 'image_url' => $response->getFilePath()], $pageAccessToken);
                $photoId = $uploadResponse->getGraphNode()['id'] ?? null;
                if ($photoId) {
                    $photoIds[] = $photoId;
                } else {
                    $errors[] = "Failed to get photo ID for image: $imagePath";
                }

            } catch (Exception $e) {
                $errors[] = sprintf("Error uploading image %s: %s", $imagePath, $e->getMessage());
                continue;
            }
        }


        try {
            // creating container for carasoul
            $carouselParams = [
                'media_type'   => 'CAROUSEL',
                'children'     => implode(',', $photoIds),
                'caption'      => $message,
            ];
            $containerResponse = $this->fb->post('/' . $pageId . '/media', $carouselParams, $pageAccessToken);
            $containerId = $containerResponse->getGraphNode()['id'] ?? null;

            if (!$containerId) {
                throw new Exception("Failed to get container ID for image:");
            }
        } catch (Exception $e) {
            $errors[] = sprintf("Error uploading image %s: %s", $imagePath, $e->getMessage());
            log_message('warning', 'Image upload warnings: ' . implode(', ', $errors));
        }


        return $containerId;
    }

/**
     * Create a feed post with attached media
     */
    private function createFeedPost(string $photoIds, string $pageId, string $pageAccessToken, $message): array
    {
        $params = [
                    'creation_id' =>  $photoIds
        ];

        $postResponse = $this->fb->post('/' . $pageId . '/media_publish', $params, $pageAccessToken);
        $postId = $postResponse->getGraphNode()['id'] ?? null;

        if (!$postId) {
                return ['error' => 'Failed to create post'];
        }

        return ['success' => $postId];
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
            'instagram_status' => $graphNode,
        ];

        $fModel = new SocialConnectRepository();
        $fbdata = $fModel->createOrUpdate($data);

        return isset($fbdata['success'])
            ? ['success' => 'Instagram post has been posted successfully']
            : ['error' => 'Failed to update Instagram post'];
    }

        /**
     * Sends a GET request to the specified Facebook Graph API endpoint.
     *
     * @param string $endpoint The API endpoint to send the GET request to.
     * @param string|null $accessToken Optional access token for authenticating the request. Defaults to null.
     * @return array|mixed The response from the Facebook Graph API. Returns an associative array with an 'error' key if an exception occurs.
     */
    public function get($endpoint, $accessToken = null)
    {
        try {
            return $this->fb->get($endpoint, $accessToken);
        } catch (FacebookResponseException|FacebookSDKException|Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Create a feed post with attached media  [NOT IN USE , TO BE REMOVED IN FUTURE]
     *
     */
    private function createCarasoulFeedPost(array $photoIds, string $pageId, string $pageAccessToken, ?string $message): array
    {
        try {
             $uploadResponse = $this->fb->post('/' . $pageId . '/media', ['media_type' => 'CAROUSEL', 'children' => $response->getFilePath()], $pageAccessToken);

            $params = [
                    'caption' => $message ?? "",
                    'creation_id' => array_map(fn($photoId) => ['media_fbid' => $photoId], $photoIds)

                ];

                $postResponse = $this->fb->post('/' . $pageId . '/media_publish', $params, $pageAccessToken);




            //$postResponse = $this->fb->post('/' . $pageId . '/media_publish', $params, $pageAccessToken);
            $postId = $postResponse->getGraphNode()['id'] ?? null;

            if (!$postId) {
                return ['error' => 'Failed to create post'];
            }

            return ['success' => $postId];

        } catch (Exception $e) {
            log_message('error', sprintf("Error creating feed post: %s", $e->getMessage()));
            return ['error' => 'Failed to create feed post'];
        }
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

}

