<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\SocialConnect\FacebookConnect;
use Tenant\app\Libraries\SocialConnect\InstagramConnect;
use App\Helpers\SyndicationUtility;
use App\Helpers\Utility;
use CodeIgniter\API\ResponseTrait;
use Tenant\app\Libraries\SocialConnect\GoogleConnect;
use Tenant\app\Libraries\SocialConnect\SocialConnectFactory;

class SocialController extends \App\Controllers\BaseController
{
    use ResponseTrait;
    private const SOCIAL_OPERATIONS = [
        'facebook' => FacebookConnect::class,
        'instagram' => InstagramConnect::class,
        'google' => GoogleConnect::class
    ];

    private $data = [];
    private const ERRORS = [
        'FAILURE' => 'Code:SM001 - Something went wrong. Please contact admin.'
    ];


    private SocialConnectFactory $social_connect_factory;

    public function __construct()
    {
        $this->social_connect_factory = new SocialConnectFactory();
    }

    /**
     * Fetch all records for social connect integrated
     * @return \CodeIgniter\HTTP\Response
     */
    public function getSocialConnect()
    {
        try {
            $this->data['accounts'] = [
                'facebook' => (new FacebookConnect())->getFacebookPages(),
                'instagram' => (new InstagramConnect())->getInstagramAccount(),
              //  'google' =>  (new GoogleConnect())->getGoogleAccount(),
            ];

            return $this->sendJsonResponse($this->data);
        } catch (\Exception $e) {
            // Log the error here
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Get Generated Facebook Link
     * @return \CodeIgniter\HTTP\Response
     */
    public function getFacebookUrl()
    {
        try {
            $this->data['url'] = (new FacebookConnect())->getLoginUrl();
            return $this->sendJsonResponse($this->data);            
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Remove Facebook data for user
     * @return \CodeIgniter\HTTP\Response
     */
    public function getFacebookUnlinked()
    {
        try {
            $this->data['msg'] = (new FacebookConnect())->unlinkAccount();
            return $this->sendJsonResponse($this->data);
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Get Generated Instagram Link
     * @return \CodeIgniter\HTTP\Response
     */
    public function getInstagramUrl()
    {
        try {
            $this->data['url'] = (new InstagramConnect())->getLoginUrl();
            return $this->sendJsonResponse($this->data);
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Remove Instagram data for user
     * @return \CodeIgniter\HTTP\Response
     */
    public function getInstagramUnlinked()
    {
        try {
            $this->data['msg'] = (new InstagramConnect())->unlinkAccount();
            return $this->sendJsonResponse($this->data);
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Post to social media dashboard
     * @param string $id
     * @param string $source
     * @return \CodeIgniter\HTTP\Response
     */
    public function postToSocial($id, $source)
    {
        if (!isset(self::SOCIAL_OPERATIONS[$source])) {
            return $this->sendJsonResponse(['error' => 'Invalid social media platform'], 400);
        }

        try {
            $socialConnectorClass = self::SOCIAL_OPERATIONS[$source];
            $socialConnector = new $socialConnectorClass();
            $response = $socialConnector->postToSocialById($id);

            if (isset($response['error'])) {
                return $this->sendJsonResponse($response, 400);
            }

            return $this->sendJsonResponse($response);
        } catch (\Exception $e) {
            log_message('error', sprintf("Error in file %s on line %d: %s", $e->getFile(), $e->getLine(), $e->getMessage()));
            return $this->sendJsonResponse(['error' => self::ERRORS['FAILURE']], 500);
        }
    }

    /**
     * Send JSON response
     * @param array $data
     * @param int $statusCode
     * @return \CodeIgniter\HTTP\Response
     */
    private function sendJsonResponse($data, $statusCode = 200): \CodeIgniter\HTTP\Response
    {
        return $this->response
            ->setContentType('application/json')
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }



    public function OauthAuthenticateUrl(string $source)
    {
        try {
            $socialConnect = $this->social_connect_factory->getInstance($source);
            $response = $socialConnect->authorize();
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->fail('Wrong source or invalid link', 400);
        }
    }


    public function revoke(string $source)
    {
        try {
            $social_connect = $this->social_connect_factory->getInstance($source);
            $social_connect->revoke();
            return $this->respond(["success" => "Token has been revoked successfully."]);
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage(), 400);
        }

    }

    public function authorize(string $source)
    {
        $data = $this->request->getGetPost();

        try {
            $social_connect = $this->social_connect_factory->getInstance($source);
            $response = $social_connect->authorize($data);

            if ($response['success']) {
                $content = $response['success'];
                unset($response['success']);
                $social_connect->saveConfigurations($response);

                $this->notifyUserWithNotification($source);

                if (is_file(APPPATH . 'Views/generic/index.php')) {
                    echo view('generic/index.php', ['content' => $content]);
                    exit;
                }
            } else {
                $content = $response['errors'];
                if (is_file(APPPATH . 'Views/generic/index.php')) {
                    echo view('generic/index.php', ['content' => $content]);
                    exit;
                }
            }
        } catch (\Exception $e) {
            return $this->fail('Integration Failed.', 400);
        }
    }

    private function notifyUserWithNotification($source)
    {
        $pushFactory = service('channelService')->getInstance("internal")['push'];
        $pushFactory->setSubject("SOCIALCONNECT:" . strtoupper($source) . ":AUTHORIZE");
        $pushFactory->setContent("");

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }


}