<?php

namespace App\Controllers;

use App\Libraries\Oauth2 as Oauth;

helper('cookie');

/**
 * AuthenticationController This controller is the centralized authentication
 * From this website, it is redirected to SSO login page, from where code and state is 
 * given back, this is then exchanged for token and the SSO sends back the token to one
 * particular URL on the main server. Once the token is fetched it is used for login session.
 */
class AuthenticationController extends BaseController
{
    /**
     * login API for login
     *
     * @return void
     */
    public function login()
    {

        Oauth::login($_SERVER['HTTP_HOST']);
    }

    //--------------------------------------------------------------------

    /**
     * exchange API for exchanging the code for token
     *
     * @return void
     */
    public function exchange()
    {

        $redirect_url = base_url();
        if ($_GET['state'] !== '') {
            $redirect_url = $_GET['state'];
        }

        $token_json = "";
        if ($_GET['code']) {
            $token_json = Oauth::exchange_code_for_token($_GET['code']);
        }
        $token_json = json_decode(trim($token_json), TRUE);
        $token_json = stripslashes(html_entity_decode($token_json));
        $json_decode = json_decode($token_json);

        if (property_exists($json_decode, "error")) {

            header('Location: ' . "https://" . $redirect_url);
            exit;
        }

        $access_token = $json_decode->access_token;
        $id_token = $json_decode->id_token;

        $access_token_validation = Oauth::token_validation($access_token, 'RS256');
        $access_token_validation = json_decode($access_token_validation);


        if (property_exists($access_token_validation, "error")) {

            header('Location: ' . "https://" . $redirect_url);
            exit;
        }

        $id_token_validation = Oauth::token_validation($id_token, 'RS256');
        $id_token_validation = json_decode($id_token_validation);

        if (property_exists($id_token_validation, "error")) {
            header('Location: ' . "https://" . $redirect_url);
            exit;
        }

        return $this->response->redirect('https://' . $redirect_url . "/api/admin/setuser?access_token=" . $access_token . "&id_token=" . $id_token);
        exit;
    }

    /**
     * details Returns the details of the logged on user for the dashboard. 
     *
     * @return void
     */
    public function details()
    {
        $authorization = apache_request_headers()["Authorization"];
        $authorization = explode(" ", $authorization);
        $id_token_validation = Oauth::token_validation($authorization[1], 'RS256');
        $id_token_validation = json_decode($id_token_validation);
        return json_encode($id_token_validation);
    }


    public function logout()
    {
        $redirect_url = $_SERVER['HTTP_HOST'];
        return $this->response
            ->setCookie(['name'   => 'token', 'value'  => "", 'expire' => '-1', 'domain' => $redirect_url, 'path'   => '/', 'prefix' => '_', 'secure' => TRUE, 'httponly' => FALSE])
            ->setCookie(['name'   => 'access_token', 'value'  => "", 'expire' => '-1', 'domain' => $redirect_url, 'path'   => '/', 'prefix' => '_', 'secure' => TRUE, 'httponly' => FALSE])
            ->removeHeader('Location')->setHeader('Location', $redirect_url)->redirect('https://' . $redirect_url);
        exit;
    }

    public function v2logout()
    {
        Oauth::logout();
    }

    public function set_cookies()
    {
        $redirect_url = $_SERVER['HTTP_HOST'];

        $access_token = isset($_GET['access_token']) ? $_GET['access_token'] : '';
        $id_token = isset($_GET['id_token']) ? $_GET['id_token'] : '';

        // Set Cookies for ID Token And Access Token And Then Redirect. 
        return $this->response
            ->setCookie(['name'   => 'token', 'value'  => $id_token, 'expire' => '1211000', 'domain' => $redirect_url, 'path'   => '/', 'prefix' => '_', 'secure' => TRUE, 'httponly' => FALSE])
            ->setCookie(['name'   => 'access_token', 'value'  => $access_token, 'expire' => '1211000', 'domain' => $redirect_url, 'path'   => '/', 'prefix' => '_', 'secure' => TRUE, 'httponly' => FALSE])
            ->removeHeader('Location')->setHeader('Location', $redirect_url)->redirect('https://' . $redirect_url);
        exit;
    }


    public function getConnectionId()
    {
        $connection_id = str_replace("_", "", db_connect()->database);
        $this->response->setContentType('application/json')->setJSON(["connection_id" => $connection_id])->send();
    }
}
