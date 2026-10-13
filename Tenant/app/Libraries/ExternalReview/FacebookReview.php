<?php

namespace Tenant\app\Libraries\ExternalReview;

use Facebook\Facebook as Facebook;

use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Interfaces\ExternalReviewInterface;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Repositories\ReviewRepository;

/**
 * Facebook   This class is a wrapper for Facebook API To Fetch Reviews
 */
class FacebookReview implements ExternalReviewInterface
{
    private $facebook;
    private $configuration;
    private $repository;
    protected $callbackUrl;
    const GRAPH_VERSION = 'v19.0';

    const FBURL = "https://www.facebook.com/";
    protected $permissions = ["pages_show_list", "pages_read_user_content"];
    public function __construct()
    {
        $this->init();
    }

    public function getRedirectHelper($customState = null)
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        $pdata = $helper->getPersistentDataHandler();
        if ($customState != null) {
            $pdata->set('state', $customState);
        } else {
            $pdata->set('state', SocialUtility::getDbNameForState());
        }

        return $helper;
    }

    public function authorize(array $data = []): ?array
    {
        if (empty($data)) {
            $helper = $this->getRedirectHelper();
            $url = $helper->getLoginUrl($this->callbackUrl, $this->permissions);
            return ['url' => $url];
        } else {
            try {
                extract($data);

                if ((isset($code) === false) || (isset($state) == false)) {
                    throw new \Exception('Missing Code. Invalid Request');
                }

                $helper = $this->getRedirectHelper($state);
                $token = $helper->getAccessToken();

                if (!empty($token)) {

                    $response = [];

                    $database_name = base64_decode($state);
                    $db = \Config\Database::connect();
                    $db->setDatabase($database_name);

                    // The OAuth 2.0 client handler helps us manage access tokens
                    $oAuth2Client = $this->facebook->getOAuth2Client();

                    // Get the access token metadata from /debug_token
                    $tokenMetadata = $oAuth2Client->debugToken($token);

                    // Validation (these will throw FacebookSDKException's when they fail)
                    $tokenMetadata->validateAppId(SocialUtility::getValueFromEnv('fb.reviews.id'));
                    $userID = $tokenMetadata->getUserId();

                    // If you know the user ID this access token belongs to, you can validate it here
                    $tokenMetadata->validateUserId($userID); //Optional
                    $tokenMetadata->validateExpiration();

                    // Exchanges a short-lived access token for a long-lived one
                    if (!$token->isLongLived()) {
                        $token = $oAuth2Client->getLongLivedAccessToken($token);
                    }

                    // Setting default token
                    $this->facebook->setDefaultAccessToken((string) $token);

                    $response['facebook_reviews_userid'] = $userID;
                    $response['facebook_reviews_refresh_token'] = $token->getValue();
                    $response['facebook_reviews_account'] = json_encode($this->getPageInfo($userID));

                    $response['success'] = 'Facebook Review API Integrated. You can close the window.';

                    return $response;
                } else {
                    throw new \Exception('Invalid Token.');
                }
            } catch (\Exception $ex) {
                return ["errors" => $ex->getMessage()];
            }
        }

        return [];
    }


    /**  
     * Return Get Page Account ID
     * 
     * @return array
     */
    private function getPageInfo($userID): ?array
    {
        $response = $this->facebook->get('/' . $userID . '/accounts');
        $pages = $response->getGraphEdge()->asArray();
        $reformatted_business_pages = [];
        if (count($pages) > 0) {
            foreach ($pages as $key => $page) {
                array_push($reformatted_business_pages, [
                    'business_id' => $page['id'],
                    'business_name' => $page['name'],
                    'business_token' => $page['access_token'],
                    'business_review_url' => 'https://www.facebook.com/' . $page['id'] . '/reviews'
                ]);
            }
        } else {
            throw new \Exception('No pages found in account');
        }

        return $reformatted_business_pages;
    }

    private function init(): void
    {
        $this->configuration = new Configuration();
        $this->repository = new ReviewRepository();

        $this->facebook = new Facebook([
            'app_id' => SocialUtility::getValueFromEnv('fb.reviews.id'),
            'app_secret' => SocialUtility::getValueFromEnv('fb.reviews.secret'),
            'default_graph_version' => static::GRAPH_VERSION,
        ]);

        $this->callbackUrl = SocialUtility::getValueFromEnv('fb.reviews.url');
    }


    public function revoke(array $options = []): array
    {
        try {
            $access_token_object = $this->getAccessTokenFromConfig();
            $userID = $this->getFbUserId();
            $response = $this->facebook->delete('/me/permissions', [], $access_token_object);
            $graphNode = $response->getGraphNode()->asArray();
            if (!$graphNode['success']) {
                throw new \Exception('Something wrong with Token');
            }
        } catch (\Exception $ex) {
            log_message('error', $ex->getMessage());
            echo $ex->getMessage();
        }

        $data = [
            'facebook_reviews_userid' => '',
            'facebook_reviews_refresh_token' => '',
            'facebook_reviews_account' => '',
            'default_facebook_review_location' => ''
        ];

        $this->configuration->setConfiguration($data);
        $this->repository->filterData("source", strtoupper(ReviewSource::FACEBOOK))->deleteWithoutKey();

        return ["success" => "Token has been revoked. Please reconnect again."];
    }

    public function saveConfigurations(array $data = []): void
    {
        $this->configuration->setConfiguration($data);
    }

    private function save($reviews)
    {
        foreach ($reviews as $review) {
            $result = $this->repository->createOrUpdate($review);
        }
    }

    private function getAccessTokenFromConfig(): ?string
    {
        $token['access_token'] = "";
        $accessToken = $this->configuration->findConfigurationColumnsByKey('facebook_reviews_refresh_token');
        return $accessToken->value;
    }

    private function getFbUserId(): ?string
    {
        $accessToken = $this->configuration->findConfigurationColumnsByKey('facebook_reviews_userid');
        return $accessToken->value;
    }

    public function getReviewURI(array $options = []): ?string
    {
        $locations = $this->getAccountPages();

        $default_location_uri = $locations[0]['business_review_url'];

        $user_user_default_location = $this->getDefaultFacebookLocation();

        if (!empty($user_user_default_location)) {
            $location_id = $user_user_default_location;
            $selected_location = array_values(array_filter($locations, function ($location) use (&$location_id) {
                return $location['business_id'] == $location_id;
            }));

            if (count($selected_location) > 0) {
                $default_location_uri = $selected_location[0]['business_review_url'];
            }
        }

        if (!empty($options['business_id'])) {
            $location_id = $options['business_id'];
            $selected_location = array_values(array_filter($locations, function ($location) use (&$location_id) {
                return $location['business_id'] == $location_id;
            }));

            if (count($selected_location) > 0) {
                $default_location_uri = $selected_location[0]['business_review_url'];
            }
        }

        return $default_location_uri;
    }

    private function getAccountPages()
    {
        $pages = $this->configuration->findConfigurationColumnsByKey('facebook_reviews_account');
        return json_decode($pages->value, true);
    }

    private function getDefaultFacebookLocation(): string
    {
        $default_google_location_object = $this->configuration->findConfigurationColumnsByKey('default_facebook_review_location');
        return $default_google_location_object->value;
    }


    public function fetch(array $options = []): void
    {
        try {
            $business_pages = $this->getAccountPages();

            foreach ($business_pages as $business_page) {
                extract($business_page);
                $this->fetchSingleBusinessPage($business_token, $business_id);
                sleep(10);
            }
        } catch (\Exception $ex) {
            echo "Error while fetching reviews for " . $ex->getMessage();
        }
    }

    private function fetchSingleBusinessPage(string $page_token, string $page_id, $current_edge = null)
    {
        try {

            $this->facebook->setDefaultAccessToken($page_token);

            $response = $this->facebook->get('/' . $page_id . '/ratings?fields=reviewer,recommendation_type,review_text,has_review,created_time,open_graph_story&limit=50');

            if (empty($current_edge)) {
                $current_edge = $response->getGraphEdge();
            }

            $reviews = $current_edge->asArray();

            if (!empty($reviews)) {
                $this->save($this->mapper($reviews));
            }

            if (!empty($current_edge->getNextCursor())) {
                $this->fetchSingleBusinessPage($page_token, $page_id, $this->facebook->next($current_edge));
            }
        } catch (\Exception $ex) {
            echo "Error while fetching reviews for " . $ex->getMessage();
        }
    }


    private function mapper(array $reviews = []): array
    {
        $normalized_reviews = [];

        foreach ($reviews as $review) {
            $current_review = [];
            $current_review['uuid'] = $review['open_graph_story']['id'];
            $current_review['profile_photo_url'] = '';
            $current_review['name'] = 'Anonymous';
            $current_review['comment'] = ($review['has_review']) ? $review['review_text'] : '';
            $current_review['reply'] = '';
            $current_review['rating'] = $this->getRating($review['recommendation_type']);
            $current_review['review_date_time'] = $review['created_time']->format('Y-m-d H:i:s');
            $current_review['review_reply_date_time'] = '';
            $current_review['source'] = strtoupper(ReviewSource::FACEBOOK);
            $current_review['location'] = $review['open_graph_story']['seller']['id'];

            array_push($normalized_reviews, $current_review);
        }

        return $normalized_reviews;
    }

    private function getRating($rating): int
    {
        $ratingsMap = [
            'negative' => 1,
            'positive' => 5
        ];

        return $ratingsMap[$rating];
    }
}
