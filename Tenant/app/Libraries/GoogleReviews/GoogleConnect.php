<?php

namespace Tenant\app\Libraries\GoogleReviews;

use Google\Client as Google;
use Tenant\app\Helpers\SocialUtility;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;

/**
 * Facebook : Pages Api class functions
 *
 * @package SocialConnect
 */
class GoogleConnect
{
    public $google;
    protected $callbackUrl;

    /**
     * Constructor for initalling required api call parameters
     */
    public function __construct()
    {
        $this->google = new Google();
        $this->google->setAuthConfig(ROOTPATH . 'ggl_secret.json');
        $this->google->addScope('https://www.googleapis.com/auth/plus.business.manage');
        $this->google->setState(static::getState());
        $this->google->setRedirectUri(SocialUtility::getValueFromEnv('google.callback.url'));
        $this->google->setAccessType('offline'); // offline access
        $this->google->setApprovalPrompt('auto'); //consent  
        $this->google->setIncludeGrantedScopes(true); // incremental auth
        $this->callbackUrl = $this->google->createAuthUrl();

    }
    public function removeGoogleToken(): array
    {
        $token = (new Configuration())->findConfigurationColumnsByKey('google_reviews');
        $accessToken = $this->getAccessTokenFromConfig();
        $res = $this->google->revokeToken($accessToken);
        if ($res) {
            $configuration = new Configuration();
            $data = [
                'google_reviews' => '',
                'google_reviews_refresh_token' => '',
                'google_reviews_account' => ''
            ];
            return $configuration->setConfiguration($data);
        }
        return ["error" => 'Invalid Token or No record found'];
    }

    public function getAccessTokenFromConfig(): string
    {
        $token['access_token'] = "";
        $accessToken = (new Configuration())->findConfigurationColumnsByKey('google_reviews');
        if ($accessToken->value != "") {
            $token = json_decode($accessToken->value, true);
        }
        return $token['access_token'];
    }

    public function getLocations($withAccount = false)
    {
        $locations = (new Configuration())->findConfigurationColumnsByKey('google_reviews_account');
        $locations = json_decode($locations->value);
        $data = [];
        foreach ($locations->business_data as $key => $val){        
          $data[]= ($withAccount) ? $val->business_location_id  : $val ;                        
        }
        return $data;
    }    

    public function getBusinessDetails()
    {
        $locations = (new Configuration())->findConfigurationColumnsByKey('google_reviews_account');  
        $locations = ($locations->value != "") ? json_decode($locations->value) : "";         
        return $locations;
    }   

    public function getBusinessAccountId()
    {
        $locations = (new Configuration())->findConfigurationColumnsByKey('google_reviews_account');
        $locations = json_decode($locations->value);
        return $locations->business_account_id;
    } 


    
    public function getRefreshToken() : string
    {
        $refresh_token = (new Configuration())->findConfigurationColumnsByKey('google_reviews_refresh_token');
        return $refresh_token->value;
    } 

    protected static function getState()
    {
        return SocialUtility::getDbNameForState();
    }

    public function getGoogleLoginUrl(): string
    {
        return $this->callbackUrl;
    }

    public function refreshToken()
    {
        $refreshTokenSaved = $this->getRefreshToken();
        // update access token
        $this->google->fetchAccessTokenWithRefreshToken($refreshTokenSaved);

        // pass access token to some variable
         $accessTokenUpdated = $this->google->getAccessToken();

        // append refresh token
        $accessTokenUpdated['refresh_token'] = $refreshTokenSaved;
  
        //Set the new acces token
        $this->google->setAccessToken($accessTokenUpdated);
 
        (new Configuration())->setConfiguration(['google_reviews' => json_encode($accessTokenUpdated)]);

    }    

    public function getRating($rating): int
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
   
}