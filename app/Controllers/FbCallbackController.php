<?php

namespace App\Controllers;

use Exception;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Libraries\SocialConnect\FacebookConnect;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;

/**
 * FbCallbackController handles the callback from Facebook for authentication and data retrieval.
 *
 */
class FbCallbackController extends BaseController
{
    private FacebookConnect $fbConnect;
    private const SOURCE = 'fb';

    public function __construct()
    {
        $this->fbConnect = new FacebookConnect();
    }

    /**
     * Retrieves the Facebook token.
     *
     * This method is responsible for handling the callback from Facebook
     * and extracting the access token from the response.
     *
     * @return void
     */
    public function getFbToken()
    {
        $request = \Config\Services::request();
        $state = $request->getGet('state');

        try {
            $accessToken = $this->getAccessToken($state);
            $longLivedAccessToken = $this->getLongLivedAccessToken($accessToken);
            $this->fbConnect->setDefaultAccessToken((string) $longLivedAccessToken);

            $userID = $this->getUserId($longLivedAccessToken);
            $page = $this->getPage($userID);

            if (empty($page)) {
                throw new Exception('No pages found in account');
            }

            //echo "<pre>";print_r($page);echo "</pre>";
            $this->saveFacebookData($state, $userID, $longLivedAccessToken, $page);
            echo "<h1>Successfully Integrated, Please close tab now.</h1>";

        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    /**
     * Retrieves the access token from Facebook's authentication process.
     *
     * @param string $state The state parameter to validate the request.
     * @return \Facebook\Authentication\AccessToken The access token object.
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
     * Retrieves a long-lived access token from a short-lived access token.
     *
     * @param \Facebook\Authentication\AccessToken $accessToken The short-lived access token.
     * @return \Facebook\Authentication\AccessToken The long-lived access token.
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
     * Retrieves the user ID from the given Facebook access token.
     *
     * @param \Facebook\Authentication\AccessToken $accessToken The access token provided by Facebook.
     * @return string The user ID associated with the access token.
     */
    private function getUserId(\Facebook\Authentication\AccessToken $accessToken): string
    {
        $oAuth2Client = $this->fbConnect->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        return $tokenMetadata->getUserId();
    }

    /**
     * Retrieves the page information for a given user.
     *
     * @param string $userId The ID of the user whose page information is to be retrieved.
     * @return array An array containing the page information for the specified user.
     */
    private function getPage(string $userId): array
    {
        $response = $this->fbConnect->get('/' . $userId . '/accounts');
        $pages = $response->getGraphEdge()->asArray();
        return count($pages) > 0 ? $pages[0] : [];
    }

    /**
     * Saves the Facebook data to the database.
     *
     * @param string $state The state parameter used to identify the database.
     * @param string $userId The ID of the user whose data is being saved.
     * @param \Facebook\Authentication\AccessToken $accessToken The access token associated with the user.
     * @param array $page The page information associated with the user.
     * @return void
     */
    private function saveFacebookData(string $state, string $userId, \Facebook\Authentication\AccessToken $accessToken, array $page): void
    {
        $data = [
            'social_facebook_userid' => $userId,
            'social_facebook_usertoken' => (string) $accessToken,
            'social_facebook_pages' => json_encode($page),
        ];

        $database = base64_decode($state);
        $this->dbase = \Config\Database::connect();
        $this->dbase->setDatabase($database);
        $configuration = new Configuration();
        $records = $configuration->setConfiguration($data);

        if (!isset($records['success'])) {
            throw new Exception("Failed to save Facebook Data");
        }
    }

    /**
     * Handles errors during the Facebook authentication process.
     *
     * @param $helper The Facebook helper object.
     * @return void
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
     * Handles exceptions and logs errors.
     *
     * @param Exception $e The exception object.
     * @return void
     */
    private function handleException(Exception $e): void
    {
        log_message('error', 'Facebook Integration Error: ' . $e->getMessage());
        http_response_code(500);
        echo 'ERROR : ' . $e->getMessage();
        exit;
    }
}