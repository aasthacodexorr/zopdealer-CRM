<?php

namespace App\Controllers;

use Exception;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Libraries\SocialConnect\InstagramConnect;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;

/**
 * The `IgCallbackController` class is a controller designed to handle the Instagram API callback flow.
 * It manages the retrieval, validation, and long-lived storage of access tokens, as well as the integration
 * of Instagram accounts linked with Facebook pages.
 */
class IgCallbackController extends BaseController
{
    /**
     * Represents a connection instance to Facebook services or API.
     */
    private InstagramConnect $fbConnect;
    private const SOURCE = 'fb';
    /**
     * Initializes the class with a new instance of InstagramConnect.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fbConnect = new InstagramConnect();
    }

    /**
     * Handles the process of retrieving and storing the Instagram token by interacting with Facebook's API.
     *
     * @return void
     */
    public function getIgToken()
    {
        $request = \Config\Services::request();
        $state = $request->getGet('state');

        try {
            $accessToken = $this->getAccessToken($state);
            $longLivedAccessToken = $this->getLongLivedAccessToken($accessToken);
            $this->fbConnect->setDefaultAccessToken((string)$longLivedAccessToken);

            $userId = $this->getUserId($longLivedAccessToken);
            $instagramId = $this->getInstagramId($userId);

            if (empty($instagramId)) {
                throw new Exception('No instagram account linked with facebook pages');
            }

            $instagramAccount = $this->getInstagramAccount($instagramId);
           // echo "<pre>";print_r($instagramAccount);echo "</pre>";
            $this->saveInstagramData($state, $userId, $longLivedAccessToken, $instagramId, $instagramAccount);

            echo "<h1>Successfully Integrated, Please close tab now.</h1>";

        } catch (Exception $e) {
            $this->handleException($e);
        }
    }


    /**
     * Retrieves the access token using the provided state.
     *
     * @param string $state The state parameter used to validate the request and retrieve the access token.
     * @return \Facebook\Authentication\AccessToken The access token retrieved from the Facebook Redirect Helper.
     */
    private function getAccessToken($state): \Facebook\Authentication\AccessToken
    {
        $helper = $this->fbConnect->getRedirectHelper($state);
        $accessToken = $helper->getAccessToken();

        if (!isset($accessToken)) {
            $this->handleFacebookError($helper);
        }

        return $accessToken;
    }

    /**
     * Extends the lifespan of a given Facebook access token if it is not already long-lived.
     *
     * @param \Facebook\Authentication\AccessToken $accessToken The short-lived or long-lived Facebook access token to be validated and potentially extended.
     * @return \Facebook\Authentication\AccessToken The long-lived Facebook access token.
     */
    private function getLongLivedAccessToken(\Facebook\Authentication\AccessToken $accessToken): \Facebook\Authentication\AccessToken
    {
        $oAuth2Client = $this->fbConnect->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId(SocialUtility::getValueFromEnv(self::SOURCE.'.app.id'));
        $tokenMetadata->validateExpiration();

        return !$accessToken->isLongLived() ? $oAuth2Client->getLongLivedAccessToken($accessToken) : $accessToken;
    }


    /**
     * Retrieves the user ID associated with the given access token.
     *
     * @param \Facebook\Authentication\AccessToken $accessToken The access token to be debugged for user information.
     * @return string The user ID associated with the provided access token.
     */
    private function getUserId(\Facebook\Authentication\AccessToken $accessToken): string
    {
        $oAuth2Client = $this->fbConnect->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        return $tokenMetadata->getUserId();
    }


    /**
     * Retrieves the Instagram Business Account ID associated with a given Facebook user ID.
     *
     * @param string $userId The ID of the Facebook user whose Instagram Business Account ID is to be retrieved.
     * @return string The Instagram Business Account ID if found, or an empty string if not available.
     */
    private function getInstagramId(string $userId): string
    {
        $response = $this->fbConnect->get('/' . $userId . '/accounts');
        $pages = $response->getGraphEdge()->asArray();
        foreach ($pages as $page) {
            $responseID = $this->fbConnect->get('/' . $page['id'] . '?fields=instagram_business_account');
            $igIdData = $responseID->getGraphNode()->asArray();
            if (isset($igIdData['instagram_business_account']['id'])) {
                return $igIdData['instagram_business_account']['id'];
            }
        }

        return '';
    }

    /**
     * Retrieves the details of an Instagram account using the provided Instagram ID.
     *
     * @param string $instagramId The ID of the Instagram account to fetch details for.
     * @return array An associative array containing the Instagram account details such as name and username.
     * @throws Exception If the Instagram account data cannot be accessed.
     */
    private function getInstagramAccount(string $instagramId): array
    {
        $responseAccount = $this->fbConnect->get('/' . $instagramId . '?fields=name,username');

        if (!is_object($responseAccount)) {
            throw new Exception('Not able to access instagram account.Please contact support.');
        }

        return $responseAccount->getGraphNode()->asArray();
    }


    /**
     * Saves Instagram account data linked to a Facebook user into the database.
     *
     * @param string $state The base64-encoded database state.
     * @param string $userId The Facebook user ID.
     * @param \Facebook\Authentication\AccessToken $accessToken The Facebook access token.
     * @param string $instagramId The Instagram account ID.
     * @param array $instagramAccount An associative array containing Instagram account details such as 'name' and 'username'.
     * @return void
     * @throws Exception If saving Instagram data fails.
     */
    private function saveInstagramData(string $state, string $userId, \Facebook\Authentication\AccessToken $accessToken, string $instagramId, array $instagramAccount): void
    {
        $data['social_instagram'] = json_encode([
            'fb_userid' => $userId,
            'fb_usertoken' => (string)$accessToken,
            'instagram_id' => $instagramId,
            'business_name' => $instagramAccount['name'],
            'instagram_username' => $instagramAccount['username'],
        ]);

        $database = base64_decode($state);
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($database);
        $configuration = new Configuration();
        $records = $configuration->setConfiguration($data);

        if (!isset($records['success'])) {
            throw new Exception("Failed to save Instagram Data");
        }
    }


    /**
     * Handles errors returned by the Facebook helper during request processing.
     *
     * @param mixed $helper The Facebook helper instance, which may contain error details.
     * @return void This method does not return any value. It sends an HTTP response and terminates execution.
     */
    private function handleFacebookError($helper): void
    {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }


    /**
     * Handles an exception by logging the error, setting an HTTP response code,
     * and displaying an error message before terminating the script.
     *
     * @param Exception $e The exception to handle.
     * @return void
     */
    private function handleException(Exception $e): void
    {
        log_message('error', 'Instagram Integration Error: ' . $e->getMessage()); // Log the error for debugging
        http_response_code(500); // Set appropriate HTTP status code
        echo 'ERROR : ' . $e->getMessage(); // Display a user-friendly error message
        exit;
    }

}