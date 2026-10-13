<?php

namespace Tenant\app\Libraries\SocialConnect;

use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use Exception;
use NumberFormatter;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Models\SocialConnectListModel;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Repositories\SocialConnectRepository;
use Facebook\Facebook as Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;

//@todo Move all error to SocialConnectAbstract Class 

/**
 * FacebookConnect class handles Facebook-specific operations.
 * 
 */
class FacebookConnect extends SocialConnectAbstract
{
    /**
     * @var Facebook
     */
    private Facebook $fb;
    /**
     * @var string
     */
    protected string $callbackUrl;
    /**
     * @var string
     */
    private const GRAPH_VERSION = 'v22.0';
    /**
     * @var string
     */
    private const SOURCE = 'fb';

    /**
     * @var array
     */
    private const PERMISSIONS = [
        "pages_manage_posts",
        "pages_read_engagement",
        "pages_show_list",
        "business_management",
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
    private const SOCIAL_FACEBOOK_PAGES = 'social_facebook_pages';

    /**
     * @var string
     */
    private const SOCIAL_FACEBOOK_USERID = 'social_facebook_userid';

    /**
     * @var string
     */
    private const SOCIAL_FACEBOOK_USERTOKEN = 'social_facebook_usertoken';
    
    /**
     * @var string
     */
    private const SOCIALCONNECT_TEMPLATE = 'socialconnect_template';
    /**
     * @var string
     */
    private const MAX_IMAGE_NO = 8;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => SocialUtility::getValueFromEnv(self::SOURCE . '.app.id'),
            'app_secret' => SocialUtility::getValueFromEnv(self::SOURCE . '.app.secret'),
            'default_graph_version' => self::GRAPH_VERSION,
        ]);
        $this->callbackUrl = SocialUtility::getValueFromEnv(self::SOURCE . '.callback.url');
    }

    /**
     * Create a feed post with attached media
     */
    private function createFeedPost(?string $message, array $photoIds, string $pageId, string $pageAccessToken): array
    {
        try {
            $params = [
                'message' => $message ?? '',
                'attached_media' => array_map(fn($photoId) => ['media_fbid' => $photoId], $photoIds)
            ];

            $postResponse = $this->fb->post('/' . $pageId . '/feed', $params, $pageAccessToken);
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
     * Get the Facebook redirect login helper.
     *
     * @param string|null $customState
     * @return \Facebook\Helpers\FacebookRedirectLoginHelper
     */
    public function getRedirectHelper(?string $customState = null)
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $persistentDataHandler = $helper->getPersistentDataHandler();
        $persistentDataHandler->set('state', $customState ?? SocialUtility::getDbNameForState());
        return $helper;
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
     * Get the Facebook access token from the database.
     *
     * @return string|null
     */
    public function getAccessTokenFromDb(): ?string
    {
        return $this->getConfigurationValue(self::SOCIAL_FACEBOOK_USERTOKEN);
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
     * Make get request to facebook SDK used by FBfalllbackController
     *
     * @param $endpoint
     * @param $accessToken
     * @return array|\Facebook\FacebookResponse
     */
    public function get($endpoint, $accessToken = null)
    {
        try {
            return $this->fb->get($endpoint, $accessToken);
        } catch (FacebookResponseException | FacebookSDKException $e) {
            return ['error' => $e->getMessage()];
        }
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
     * Get the Facebook pages from the database.
     *
     * @param int $flag
     * @return array
     */
    public function getFacebookPages(int $flag = 0): array
    {
        $facebookPages = $this->getConfigurationValue(self::SOCIAL_FACEBOOK_PAGES);
        if ($facebookPages) {
            $page = json_decode($facebookPages);

            if ($page) {
                return $flag ? ['pageid' => $page->id, 'name' => $page->name, 'token' => $page->access_token] : ['pageid' => $page->id, 'name' => $page->name];
            }
        }
        return [];
    }

    /**
     * Unlink the Facebook account.
     *
     * @return array
     */
    public function unlinkAccount(): array
    {
        $configuration = new Configuration();
        $data = [
            self::SOCIAL_FACEBOOK_USERID => "",
            self::SOCIAL_FACEBOOK_USERTOKEN => "",
            self::SOCIAL_FACEBOOK_PAGES => ""
        ];

        try {
            // Revoke the access token using the Facebook SDK
            $accessToken = $this->getAccessTokenFromDb();
            if ($accessToken) {
                $this->fb->delete('/me/permissions', [], $accessToken);
            }
        } catch (FacebookResponseException | FacebookSDKException | Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
        }

        // Clear the configuration data //@todo : return void , we dot need return here
        return $configuration->setConfiguration($data);
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
     * Get the VDP URL.
     *
     * @param array $inv
     * @return string
     */
    private function getVdpUrl(array $inv): string
    {
        $bWebsite = $this->getConfigurationValue(self::BUSINESS_WEBSITE);
        $iType = $this->getConfigurationValue(self::INVENTORY_TYPE);
        $wordPressType = $this->getConfigurationValue(self::WORDPRESS_TYPE);
        return Utility::urlCreation($bWebsite, $iType, $inv, $wordPressType);
    }

    /**
     * Get the site URL.
     *
     * @return string
     */

    public function getSiteUrl(): string
    {
        return $this->getConfigurationValue(self::BUSINESS_WEBSITE);
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
     * Post to social media.
     *
     * @param string $id
     * @return array
     */
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
            $fbpage = $this->getFacebookPages(1);
            if (empty($fbpage)) {
                throw new Exception('No Facebook page configured');
            }

            $graphNode = $this->postToSocialMedia($template, $images, $fbpage);
            if (isset($graphNode['error'])) {
                throw new Exception('Error posting to Facebook' . $graphNode['error']);
            }

            return $this->updateSocialStatus($invLine['stock_no'], $graphNode['success']);

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }

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
            'facebook_status' => $graphNode,
        ];

        $fModel = new SocialConnectRepository();
        $fbdata = $fModel->createOrUpdate($data);

        return isset($fbdata['success'])
            ? ['success' => 'Facebook post has been added successfully']
            : ['error' => 'Failed to update Facebook post'];
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
        if (empty($extra['token']) || empty($extra['pageid'])) {
            return ['error' => 'Invalid page token or page ID'];
        }

        // Limit images to Facebook's maximum of 5
        $imagePaths = array_slice($imagePaths, 0, self::MAX_IMAGE_NO);

        if (empty($imagePaths)) {
            return ['error' => 'No images provided'];
        }

        try {
            $photoIds = $this->uploadImages($imagePaths, $extra['pageid'], $extra['token']);

            // Step 2: Create a message with all the uploaded images
            if (empty($photoIds)) {
                return ['error' => 'Failed to upload images'];
            }

            return $this->createFeedPost($message, $photoIds, $extra['pageid'], $extra['token']);

        } catch (FacebookResponseException | FacebookSDKException | Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return ["error" => $e->getMessage()];
        }

    }

    /**
     * Upload multiple images to Facebook
     */
    private function uploadImages(array $imagePaths, string $pageId, string $pageAccessToken): array
    {
        $photoIds = [];
        $errors = [];

        foreach ($imagePaths as $index => $imagePath) {
            try {
                $headers = get_headers($imagePath, 1);
                // Check if the HTTP status is 200 OK
                if (strpos($headers[0], '200') === false) {
                    $errors[] = "Image not found: $imagePath";
                    continue;
                }

                $response = $this->fb->fileToUpload($imagePath);
                $uploadResponse = $this->fb->post('/' . $pageId . '/photos', ['published' => 'false', 'source' => $response], $pageAccessToken);
                $photoId = $uploadResponse->getGraphNode()['id'] ?? null;
                if ($photoId) {
                    $photoIds[] = $photoId;
                } else {
                    $errors[] = "Failed to get photo ID for image: $imagePath";
                }

            } catch (Exception $e) {
                $errors[] = sprintf("Error uploading image %s: %s", $imagePath, $e->getMessage());
                log_message('error', end($errors));
                continue;
            }
        }

        if (!empty($errors)) {
            log_message('warning', 'Image upload warnings: ' . implode(', ', $errors));
        }

        return $photoIds;
    }



}