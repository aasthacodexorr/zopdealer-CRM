<?php

namespace Tenant\app\Repositories;

use App\Helpers\TenantNonPersistantCache;
use App\Helpers\Utility;
use App\Repositories\BaseRepository;
use Tenant\app\Models\ConfigurationModel;

/**
 * ConfigurationRepository : Setting, Getting and Validating Configuration
 */
class ConfigurationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new ConfigurationModel());
    }

    public function getModuleConfiguration(String $module_name)
    {
        return $this->model->where('code_scope', $module_name)->findAll();
    }

    public function getModuleConfigurationAsArray(String $module_name)
    {
        return $this->model->where('code_scope', $module_name)->asArray()->findAll();
    }

    public function getActiveModuleConfiguration(String $module_name)
    {
        return $this->model->where('code_scope', $module_name)->where('active', "1")->findAll();
    }

    public function getActiveModuleConfigurationAsArray(String $module_name)
    {
        return $this->model->where('code_scope', $module_name)->where('active', "1")->asArray()->findAll();
    }

    /**
     * setConfiguration
     *
     * @param  mixed $data
     * @param  bool $reset_cache
     * @return 
     */
    public function setConfiguration($data, bool $reset_cache = true)
    {
        $errors = [];
        $save_data = [];
        // loop through each for validation
        foreach ($data as $key => $value) {
            $row = $this->findConfigurationByKey($key);
            if (!is_null($row)) {
                if ($row->type === 'password') {
                    $value = Utility::encryptString($value, getenv("password.encryption"));
                }

                $flag = $this->validation($row->type, $value, $row->label);
                array_push($save_data, ["key" => $key, "value" => $value]);
                if (is_string($flag)) {
                    $errors[$key] = $flag;
                }
            }
        }

        if (count($errors) > 0) {
            return $errors;
        } else {
            $this->model->builder()->updateBatch($save_data, 'key');
            if ($reset_cache) {
                $this->deleteModuleBasedCache();
            }
            return ["success" => 'Settings updated successfully.'];
        }
    }

    protected function validation($type, $value, $label)
    {
        switch ($type) {
            case 'boolean':
                $bool = $this->booleanValidation($value);
                return $bool ? true : $label . " should be '0' or '1' value.";
                break;
            case 'number':
                $bool = $this->numericValidation($value);
                return $bool ? true : $label . " should be a number.";
                break;
            case 'decimal':
                $bool = $this->decimalValidation($value);
                return $bool ? true : $label . " should be a decimal value.";
                break;
            default:
                return true;
                break;
        }
    }

    protected function findConfigurationByKey($key)
    {
        return $this->model->where('key', $key)->where('active', 1)->first();
    }

    protected function booleanValidation($value)
    {
        if ($value == '1' || $value == '0') return true;

        return false;
    }

    protected function numericValidation($value)
    {
        if (is_int((int) $value)) return true;

        return false;
    }

    protected function decimalValidation($value)
    {
        if (is_numeric($value)) return true;

        return false;
    }

    /**
     * findConfigurationColumnsByKey
     *
     * @param  mixed $key Key which needs to be fetched
     * @param  array $fields columns associated with the $key
     * @return ConfigurationModel
     */
    public function findConfigurationColumnsByKey($key, $fields = ['*'])
    {
        return $this->model->select($fields)->where('key', $key)->where('active', 1)->first();
    }

    public function findConfigurationColumnsByKeyAsArray($key, $fields = ['*'])
    {
        return $this->model->select($fields)->where('key', $key)->where('active', 1)->asArray()->first();
    }

    /**
     * deleteAModuleCache
     *
     * @param  mixed $code_scope
     * @return void
     */
    public function deleteAModuleCache($code_scope): void
    {
        TenantNonPersistantCache::delete('config_' . $code_scope . '_index');
        TenantNonPersistantCache::delete('config_' . $code_scope . '_active_index');
    }

    private function deleteModuleBasedCache()
    {
        try {
            TenantNonPersistantCache::delete('phaeton_config');
            $list_of_code_scopes = ['inventory', 'syndication', 'global', 'deal', 'lead', 'invoice', 'reviews', 'social', 'workflow', 'digital_retailing', 'payment', 'voucher', 'fbads', 'website', 'activity', 'zop_studio'];
            foreach ($list_of_code_scopes as $code_scope) {
                TenantNonPersistantCache::delete('config_' . $code_scope . '_index');
                TenantNonPersistantCache::delete('config_' . $code_scope . '_active_index');
            }
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            log_message('info', $ex->getMessage());
        }
    }
}
