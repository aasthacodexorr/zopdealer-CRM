<?php

namespace Tenant\app\Controllers;

use CodeIgniter\API\ResponseTrait;
use Tenant\app\Repositories\ReviewRepository;
use Tenant\app\Repositories\ConfigurationRepository as Configuration;
use Tenant\app\Libraries\ExternalReview\ExternalReviewFactory;

class ExternalReviewController extends \App\Controllers\BaseController
{
    use ResponseTrait;

    /**
     * repository This repository corresponds to the ReviewRepository which can be used by any method here.
     *
     * @var ReviewRepository
     */

    private ReviewRepository $repository;
    private ExternalReviewFactory $external_review_factory;

    public function __construct()
    {
        $this->repository = new ReviewRepository();
        $this->external_review_factory = new ExternalReviewFactory();
    }

    public function OauthAuthenticateUrl(string $source)
    {
        try {
            $externalReview = $this->external_review_factory->getInstance($source);
            $response = $externalReview->authorize();
            return $this->respond($response);
        } catch (\Exception $e) {
            return $this->fail('Wrong source or invalid link', 400);
        }
    }

    public function authorize(string $source)
    {
        $data = $this->request->getGetPost();

        try {
            $externalReview = $this->external_review_factory->getInstance($source);
            $response = $externalReview->authorize($data);
            if ($response['success']) {
                $content = $response['success'];
                unset($response['success']);
                $externalReview->saveConfigurations($response);

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

    public function getReviewURI(string $source)
    {
        $data = $this->request->getGetPost();
        try {
            $externalReview = $this->external_review_factory->getInstance($source);
            $response = $externalReview->getReviewURI($data);
            return $this->respond(["url" => $response]);
        } catch (\Exception $e) {
            return $this->fail('Could not find Review URI.', 400);
        }
    }

    public function fetchReviews(string $source)
    {
        try {
            $externalReview = $this->external_review_factory->getInstance($source);
            $response = $externalReview->fetch();
            return $this->respond(["success" => "Reviews are updated successfully."]);
        } catch (\Exception $e) {
            return $this->fail('Failed to fetch the reviews.', 400);
        }
    }

    public function revoke(string $source)
    {
        try {
            $externalReview = $this->external_review_factory->getInstance($source);
            $externalReview->revoke();
            return $this->respond(["success" => "Token has been revoked successfully."]);
        } catch (\Exception $ex) {
            return $this->fail($ex->getMessage(), 400);
        }
    }

    private function notifyUserWithNotification($source)
    {
        $pushFactory = service('channelService')->getInstance("internal")['push'];
        $pushFactory->setSubject("REVIEW:" . strtoupper($source) . ":AUTHORIZE");
        $pushFactory->setContent("");

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];
        $pushFactory->setRecipient($app_name, "*");
        $pushFactory->send();
    }
}
