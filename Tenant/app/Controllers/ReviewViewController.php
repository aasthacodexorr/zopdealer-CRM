<?php

namespace Tenant\app\Controllers;

use App\Controllers\BaseController;
use FacebookAds\Object\ExternalEventSource;
use Tenant\app\Libraries\ExternalReview\ExternalReviewFactory;
use Tenant\app\Libraries\ExternalReview\ReviewSource;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\ReviewRepository;
use Tenant\app\Repositories\ReviewViewRepository;

class ReviewViewController extends BaseController
{
    /**
     * repository
     *
     * @var ReviewViewRepository
     */
    private $repository;

    /**
     * secondary_repository
     *
     * @var ReviewRepository
     */
    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new ReviewViewRepository();
        $this->secondary_repository = new ReviewRepository();
    }

    public function index()
    {
        $response = $this->repository->index();
        $this->response->setContentType('application/json')->setJSON(["review_views" => $response])->send();
    }

    public function create(): void
    {
        $data = $this->request->getPost();
        $response = $this->repository->create($data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $response = $this->repository->update($data, $id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function result($id)
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $response = $this->repository->result($id);
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function filters()
    {
        $result = [];

        $website_review_view_query = (new ConfigurationRepository())->findConfigurationColumnsByKey('website_review_view', 'value')->value;

        $items = 10;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        $result = $this->secondary_repository->allColumnAggregation($website_review_view_query);

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)
            ->setContentType('application/json')->setJSON($result)->send();
    }

    public function render()
    {

        $paginate = true;

        if ($this->request->getGet('paginate')) {
            $paginate  = $this->request->getGet('paginate') == 'false' ? false : true;
        }

        $website_review_view_query = (new ConfigurationRepository())->findConfigurationColumnsByKey('website_review_view', 'value')->value;

        if ($this->request->getGet('only_featured')) {
            if (is_string($website_review_view_query)) {
                $website_review_view_query = json_decode($website_review_view_query, true);
                array_push($website_review_view_query, [
                    "header" => "Featured",
                    "column" => "featured",
                    "join" => "AND",
                    "comparator" => "is",
                    "type" => "Single Select",
                    "value_1" => "1",
                    "value_2" => ""
                ]);

                $website_review_view_query = json_encode($website_review_view_query);
            }
        }

        $response = $this->repository->renderView($website_review_view_query, $paginate);
        $location = (new LocationRepository())->getLocationByName();
        $review_url = ((new ExternalReviewFactory())->getInstance(ReviewSource::GOOGLE))->getReviewURI();

        $response['review_url'] = $review_url;

        $response['business_name'] = $location['business_name'];
        $response['complete_address'] = $location['street'] . ', ' . $location['city'] . ', ' . $location['state']
            . ', ' . $location['zipcode'];

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)
            ->setContentType('application/json')->setJSON($response)->send();
    }
}
