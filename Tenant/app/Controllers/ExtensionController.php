<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\ConfigurationRepository;

final class ExtensionController extends \App\Controllers\BaseController
{
    private ConfigurationRepository $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function validateApiToken()
    {
        $url_params  = $this->request->getGet();
        $extension_crawler_token = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('extension_crawler_token', 'value');

        if (!empty($url_params['token']) && $url_params['token'] === $extension_crawler_token['value']) {
            $this->response->setContentType('application/json')->setJSON(['success' => 'Authorization Token is valid.'])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('401')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function getEnabledVendorsList()
    {
        $url_params  = $this->request->getGet();
        $extension_crawler_token = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('extension_crawler_token', 'value');

        if (!empty($url_params['token']) && $url_params['token'] === $extension_crawler_token['value']) {
            $enabled_vendors_list_object = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('extension_crawler_vendors', 'value');
            $enabled_vendors_list = json_decode($enabled_vendors_list_object['value'], true);
            $this->response->setContentType('application/json')->setJSON([
                'enabled_vendors_list' => $enabled_vendors_list ? $enabled_vendors_list : [],
            ])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('401')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
