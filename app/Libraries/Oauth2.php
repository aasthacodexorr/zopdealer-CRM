<?php

namespace App\Libraries;

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;


/**
 * Oauth2 = Wrapper for Building New Authentication API.
 */
class Oauth2
{

    /**
     * login function builds url and redirects to the SSO login Page for Auth0.
     *
     * @param  mixed $state : OPTIONAL Parameter for the Redirect URL.
     * @return void
     */
    public static function login($state = '')
    {

        $__url = 'https://';
        $__url .= getenv('OAuth2_DOMAIN');
        $__url .= '/rest/authorize?';
        $__url .= 'response_type=';
        $__url .= 'code&';
        $__url .= 'client_id=';
        $__url .= getenv('OAuth2_CLIENT_ID');
        $__url .= '&redirect_uri=';
        $__url .= getenv('OAuth2_REDIRECT_URI');
        $__url .= '&scope=openid profile app user email';
        $__url .= '&state=';
        $__url .= $state;
        $__url .= '&connection=';
        // $__url .= 'Zop-Dealers-Users';
        $__url .=  str_replace("_", "", db_connect()->database);


        header('Location: ' . $__url);
        exit;
    }

    /**
     * exchange_code_for_token : This method is the second step of OAuth0 Authentication.
     * Code is exchanged for the token. Access Token Is Spit out by this request as well Id token. 
     * Access Token is used for authentication and ID Token has the claims we are asking for the
     * grants.Method returns response for the token.
     *
     * @param  mixed $code
     * @return $response 
     */
    public static function exchange_code_for_token($code)
    {
        $curl = curl_init();

        $__url = "https://" . getenv('OAuth2_DOMAIN') . "/rest/token";

        $__post  = "grant_type=";
        $__post .= "authorization_code";
        $__post .= "&client_id=";
        $__post .= getenv('OAuth2_CLIENT_ID');
        $__post .= "&client_secret=";
        $__post .= getenv('OAuth2_CLIENT_SECRET');
        $__post .= '&code=';
        $__post .= $code;
        $__post .= "&redirect_uri=";
        $__post .= getenv('OAuth2_REDIRECT_URI');

        $__request_type = "POST";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $__url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $__request_type,
            CURLOPT_POSTFIELDS => $__post,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        #curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $error = array('error' => $err);
            return json_encode($error);
        } else {
            return json_encode($response);
        }
    }

    /**
     * id_token_validation : Checks for the validity of access token as well as ID Token.
     * (OPTIONAL) Check for access token 
     * Check for id token and retrieve the claim.
     *
     * @param  mixed $id_token
     * @param  mixed $token_alg
     * @return String 
     */
    public static function token_validation($id_token, $token_alg)
    {
        try {
            $public_key = file_get_contents(__DIR__ . '/public.key');
            $decoded = JWT::decode($id_token,   new Key($public_key, 'RS256'));
            return json_encode((array) $decoded);
        } catch (\Exception $e) {
            $error = array('error' => $e->getMessage());
            return json_encode($error);
        }
    }

    public static function logout() {}
}
