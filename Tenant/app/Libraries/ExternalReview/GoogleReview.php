<?php

namespace Tenant\app\Libraries\ExternalReview;

use Google\Client;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Interfaces\ExternalReviewInterface;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Repositories\ReviewRepository;

/**
 * Google   This class is a wrapper for Google API To Fetch Reviews
 */
class GoogleReview implements ExternalReviewInterface
{
    private $google;
    private $configuration;
    private $repository;

    public function __construct()
    {
        $this->init();
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
                    $response['google_reviews'] = json_encode($token);

                    $parent_account_id = $this->getParentAccountID($this->google);

                    $locations = $this->getBussinessLocations($this->google, $parent_account_id);

                    $response['google_reviews_account'] = json_encode($locations);

                    if ($this->google->getRefreshToken() != null) {
                        $response['google_reviews_refresh_token'] = $this->google->getRefreshToken();
                    }

                    $response['success'] = 'Google Review API Integrated. You can close the window.';
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

    public function fetch(array $options = []): void
    {
        try {
            $access_token_object = $this->getAccessTokenFromConfig();

            if (!empty($access_token_object)) {

                $locations = $this->getBusinessAccountsList();


                $this->google->setAccessToken($access_token_object);

                if ($this->google->isAccessTokenExpired()) {
                    $this->refreshToken();
                    $access_token_object = $this->getAccessTokenFromConfig();
                }

                foreach ($locations as $location) {
                    extract($location);
                    $this->fetchSingleLocationReviews($parent_account_id, $business_location_id, $access_token_object['access_token']);
                    Sleep(5);
                }
            }
        } catch (\Exception $ex) {
            echo "Error while fetching reviews for " . $ex->getMessage();
        }
    }

    public function getReviewURI(array $options = []): ?string
    {
        $locations = $this->getBusinessAccountsList();

        $default_location_uri = $locations[0]['business_review_url'];

        $user_user_default_location = $this->getDefaultGoogleLocation();

        if (!empty($user_user_default_location)) {
            $location_id = $user_user_default_location;
            $selected_location = array_values(array_filter($locations, function ($location) use (&$location_id) {
                return $location['business_location_id'] == $location_id;
            }));

            if (count($selected_location) > 0) {
                $default_location_uri = $selected_location[0]['business_review_url'];
            }
        }

        if (!empty($options['business_location_id'])) {
            $location_id = $options['business_location_id'];
            $selected_location = array_values(array_filter($locations, function ($location) use (&$location_id) {
                return $location['business_location_id'] == $location_id;
            }));

            if (count($selected_location) > 0) {
                $default_location_uri = $selected_location[0]['business_review_url'];
            }
        }

        return $default_location_uri;
    }

    public function revoke(array $options = []): array
    {
        $access_token_object = $this->getAccessTokenFromConfig();
        $this->google->setAccessToken($access_token_object);
        $this->google->revokeToken();
        $data = [
            'google_reviews' => '',
            'google_reviews_refresh_token' => '',
            'google_reviews_account' => '',
            'default_google_review_location' => ''
        ];

        $this->configuration->setConfiguration($data);

        $this->repository->filterData("source", strtoupper(ReviewSource::GOOGLE))->deleteWithoutKey();

        return ["success" => "Token has been revoked. Please reconnect again."];
    }

    public function saveConfigurations(array $data = []): void
    {
        $this->configuration->setConfiguration($data);
    }


    private function init(): void
    {
        $this->configuration = new Configuration();
        $this->repository = new ReviewRepository();
        $this->google = new Client();
        $this->google->setAuthConfig(ROOTPATH . 'ggl_secret.json');
        $this->google->addScope('https://www.googleapis.com/auth/plus.business.manage');
        $this->google->setState(SocialUtility::getDbNameForState());
        $this->google->setRedirectUri(SocialUtility::getValueFromEnv('google.callback.url'));
        $this->google->setAccessType('offline'); // offline access
        $this->google->setApprovalPrompt('force'); //consent  
        $this->google->setIncludeGrantedScopes(true); // incremental auth
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

    private function fetchSingleLocationReviews(string $parent_account_id, string $location, string $access_token, array $options = [])
    {
        try {
            $page_token = $options['page_token'] ?? '';
            $url = "https://mybusiness.googleapis.com/v4/{$parent_account_id}/{$location}/reviews?access_token={$access_token}&pageToken={$page_token}&pageSize=50";
            $json_response = file_get_contents($url);
            if ($json_response) {
                $data = json_decode($json_response, true);
                if (!empty($data)) {
                    $this->save($this->mapper($data['reviews']));
                    if (!empty($data['nextPageToken'])) {
                        $this->fetchSingleLocationReviews($parent_account_id, $location, $access_token, ['page_token' => $data['nextPageToken']]);
                    }
                }
            }
        } catch (\Exception $ex) {
            echo "Error while fetching reviews for " . $location . $ex->getMessage();
        }
    }

    private function save($reviews)
    {
        foreach ($reviews as $review) {
            $result = $this->repository->createOrUpdate($review);
        }
    }

    private function mapper(array $reviews = []): array
    {
        $normalized_reviews = [];

        foreach ($reviews as $review) {

            $current_review = [];
            $current_review['uuid'] = $review['reviewId'];
            $current_review['profile_photo_url'] = $review['reviewer']['profilePhotoUrl'];
            $current_review['name'] = $review['reviewer']['displayName'];
            $current_review['comment'] = !empty($review['comment']) ? $review['comment'] : '';
            $current_review['reply'] = !empty($review['reviewReply']['comment']) ? $review['reviewReply']['comment'] : '';
            $current_review['rating'] = $this->getRating($review['starRating']);
            $current_review['review_date_time'] = $review['createTime'];
            $current_review['review_reply_date_time'] = isset($review['reviewReply']['updateTime']) ? $review['reviewReply']['updateTime'] : '';
            $current_review['source'] = strtoupper(ReviewSource::GOOGLE);
            $l = preg_split("#/#", $review['name']);
            $current_review['location'] = $l[3];

            array_push($normalized_reviews, $current_review);
        }

        return $normalized_reviews;
    }

    private function getRating($rating): int
    {
        $ratingsMap = [
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5
        ];

        return $ratingsMap[$rating];
    }

    private function getAccessTokenFromConfig(): ?array
    {
        $token['access_token'] = "";
        $accessToken = $this->configuration->findConfigurationColumnsByKey('google_reviews');
        return json_decode($accessToken->value, true);
    }

    private function getBusinessAccountsList(): array
    {
        try {
            $locations = $this->configuration->findConfigurationColumnsByKey('google_reviews_account');
            return json_decode($locations->value, true);
        } catch (\Exception $ex) {
            return [];
        }
    }

    private function getDefaultGoogleLocation(): string
    {
        $default_google_location_object = $this->configuration->findConfigurationColumnsByKey('default_google_review_location');
        return $default_google_location_object->value;
    }

    private function refreshToken()
    {
        $current_refresh_token = $this->getRefreshToken();
        $this->google->fetchAccessTokenWithRefreshToken($current_refresh_token);
        $updated_access_token = $this->google->getAccessToken();
        $updated_access_token['refresh_token'] = $current_refresh_token;

        $this->google->setAccessToken($updated_access_token);
        $this->configuration->setConfiguration(['google_reviews' => json_encode($updated_access_token)]);
        $this->configuration->setConfiguration(['google_reviews_refresh_token' => $updated_access_token['refresh_token']]);
    }

    private function getRefreshToken(): string
    {
        $refresh_token = $this->configuration->findConfigurationColumnsByKey('google_reviews_refresh_token');
        return $refresh_token->value;
    }
}
