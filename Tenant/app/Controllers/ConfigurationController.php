<?php

namespace Tenant\app\Controllers;

use App\Helpers\TenantNonPersistantCache;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\ImageRepository;
use Tenant\app\Repositories\InventoryRepository;

/**
 * Configuration : Controller for managing the configuration of the tenant / client.
 */
class ConfigurationController extends \App\Controllers\BaseController
{
    /**
     * repository Represents the Configuration Repository which is shared across the methods here.
     *
     * @var ConfigurationRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new ConfigurationRepository();
    }

    /**
     * index This method list all the configurations for a particular module.
     *
     * @param  string $module Represents the name of the module.
     * @return void
     */
    public function index(String $module)
    {
        $response = $this->getCachedData('config_' . $module . '_index');

        if (empty($response)) {
            $response = $this->repository->getModuleConfigurationAsArray($module);
            $this->saveCachedData('config_' . $module . '_index', $response);
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * activeConfiguration This method shows only active configurations for a particular module.
     *
     * @param  string $module Represents the name of the module.
     * @return void
     */
    public function activeConfiguration(String $module)
    {
        $response = $this->getCachedData('config_' . $module . '_active_index');

        if (empty($response)) {
            $response = $this->repository->getActiveModuleConfigurationAsArray($module);
            $this->saveCachedData('config_' . $module . '_active_index', $response);
        }

        $this->response->setContentType('application/json')->setJSON($response)->send();
    }


    /**
     * getConfigurationByKey This method returns configuration by key
     *
     * @param  mixed $module
     * @return void
     */
    public function getConfigurationByKey(String $key)
    {
        $response = $this->repository->findConfigurationColumnsByKey($key);
        $this->response->setContentType('application/json')->setJSON($response)->send();
    }

    /**
     * update This method accepts the Post Data which are the configurations that has to be 
     * updated.
     *
     * @return void
     */
    public function update()
    {
        $data = $this->request->getPost();

        $restricted_key_list = $this->restrictedKeyList();

        foreach ($restricted_key_list as $restricted_key) {
            if (key_exists($restricted_key, $data)) {
                unset($data[$restricted_key]);
            }
        }

        $this->actionOnAIStudioConfigurationChanges($data);

        $response = $this->repository->setConfiguration($data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    public function getCachedData(String $key)
    {
        $data = TenantNonPersistantCache::get($key);
        if (!empty($data)) {
            return $data;
        }
    }

    public function saveCachedData(String $key, $data)
    {
        TenantNonPersistantCache::save($key, $data, 900);
    }

    private function restrictedKeyList(): array
    {
        return ['zop_studio_available_credit', 'zop_studio_monthly_recurring_credit'];
    }

    private function actionOnAIStudioConfigurationChanges(array $data): void
    {
        try {
            $configurations_relevant_for_processing_ai_images = ['zop_studio_show_shadow', 'zop_studio_mask_number_plate', 'zop_studio_background_image', 'enable_zop_studio'];
            foreach ($configurations_relevant_for_processing_ai_images as $key) {
                if (isset($data[$key])) {
                    $current_config = $this->repository->findConfigurationColumnsByKeyAsArray($key, ['value']);
                    $current_config_value = $current_config['value'];

                    if ($current_config_value != $data[$key]) {

                        if ($key === 'enable_zop_studio') {
                            $has_zop_studio_been_enabled = !empty($data[$key]);

                            if ($has_zop_studio_been_enabled) {
                                $overlay_image = (new ImageRepository())->getActiveOverlayImages();
                                if (count($overlay_image) > 0) {
                                    (new ImageRepository())->changeActivation($overlay_image[0]['id'], "0");
                                }

                                (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay(false);
                            } else {
                                (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay(true);
                            }
                        } else {
                            (new InventoryRepository())->purgeDeleteInventoryImagesWithOverlay(false);
                        }

                        break;
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }
    }
}
