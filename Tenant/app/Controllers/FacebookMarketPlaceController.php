<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\FacebookMarketPlaceRepository;

class FacebookMarketPlaceController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the Update or Create logic for Facebook Marketplace logs table.
     *
     * @var FacebookMarketPlaceRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new FacebookMarketPlaceRepository();
    }


    public function show($id)
    {
        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
            $response = $this->repository->show($id);
            if (isset($response)) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Post Id couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function delete($id)
    {
        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Post ID has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function createOrUpdate()
    {

        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {

            unset($data['token']);
            $response = $this->repository->createOrUpdate($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function index()
    {
        if (service('authPolicy')->hasElevatedRoles()) {

            $this->repository->setModel(new FacebookMarketplaceListModel());

            $items = 100;
            $paginate = true;


            $column = 'created_at';
            $order = 'DESC';


            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }

            if ($this->request->getGet('page_no')) {
                $_GET['page'] = $this->request->getGet('page_no');
            }

            if ($this->request->getGet('paginate')) {
                $paginate = $this->request->getGet('paginate');
                $paginate  = $paginate == 'false' ? false : true;
            }

            if ($this->request->getGet('column')) {
                $column = $this->request->getGet('column');
            }

            if ($this->request->getGet('order')) {
                $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
            }


            $data = $this->repository;

            if ($this->request->getGet('search')) {
                $search = $this->request->getGet('search');
                if ($search != '') {
                    $data = $this->repository->search($search);
                }
            }

            $sort = '{"' . $column . '" : "' . $order  . '"}';

            $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'facebook_marketplace_view_id', 'facebook_marketplace_listing');

            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }


    public function multipleDeletes()
    {
        if (service('authPolicy')->hasElevatedRoles()) {

            $data = $this->request->getPost();

            $data['post_ids'] = explode(',', $data['post_ids']);

            if (count($data['post_ids']) > 0) {
                $this->repository->delete($data['post_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function filterDataAndPurgeDelete()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            try {

                $data = $this->request->getPost();

                $column = isset($data['column']) ?  $data['column'] : '';
                $value = isset($data['value']) ?  $data['value'] : '';

                $this->repository->filterDataAndPurgeDelete($column, $value);
                $this->response->setContentType('application/json')->setJSON([
                    'success' => 'Facebook Marketplace Logs have been deleted successfully'
                ])->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function changeStatusFromToBeUpdatedToUpdated()
    {
        if (service('authPolicy')->isSuperOwner()) {
            try {

                $this->repository->changeStatusFromToBeUpdatedToUpdated();
                $this->response->setContentType('application/json')->setJSON([
                    'success' => 'Facebook Marketplace Logs status has been updated.'
                ])->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function updateDefaultFacebookCategories()
    {
        (new ConfigurationRepository())->setConfiguration([
            'all_facebook_categories' => "[{\"name\":\"Car/Truck\",\"value\":\"Car/Truck\"},{\"name\":\"Trailer\",\"value\":\"Trailer\"},{\"name\":\"RV/Camper\",\"value\":\"RV/Camper\"},{\"name\":\"Commercial/Industrial\",\"value\":\"Commercial/Industrial\"},{\"name\":\"Motorcycle\",\"value\":\"Motorcycle\"},{\"name\":\"Powersport\",\"value\":\"Powersport\"},{\"name\":\"Boat\",\"value\":\"Boat\"},{\"name\":\"Other\",\"value\":\"Other\"}]",
        ]);

        $this->response->setContentType('application/json')->setJSON([
            'success' => 'FBMP Default Categories have been updated'
        ])->send();
    }

    public function getCredentials()
    {

        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {
            $facebook_email_address = (new ConfigurationRepository())->findConfigurationColumnsByKey('facebook_email_address', 'value');
            $facebook_email_address = $facebook_email_address->value;

            $facebook_password = (new ConfigurationRepository())->findConfigurationColumnsByKey('facebook_password', 'value');
            $facebook_password = $facebook_password->value;

            $facebook_password = Utility::decryptString($facebook_password, getenv("password.encryption"));

            $this->response->setContentType('application/json')->setJSON(compact("facebook_email_address", "facebook_password"))->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function sendNotification()
    {
        $data = $this->request->getPost();
        $rpa_token = (new ConfigurationRepository())->findConfigurationColumnsByKey('rpa_token', 'value');

        $db = \Config\Database::connect();
        $app_builder = $db->table('app')->get()->getRowArray();
        $app_name = $app_builder['name'];

        if (!empty($data['token']) && $data['token'] === $rpa_token->value) {

            unset($data['token']);
            $content = !empty($data['content']) ? $data['content'] : '';
            $pushFactory = service('channelService')->getInstance("internal")['push'];
            $pushFactory->setSubject("RPA:FBMP:NOTIFICATION");
            $pushFactory->setRecipient($app_name, "*");
            $pushFactory->setContent($content);
            $pushFactory->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
