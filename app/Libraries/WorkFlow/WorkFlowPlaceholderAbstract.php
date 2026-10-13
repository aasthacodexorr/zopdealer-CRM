<?php

namespace App\Libraries\WorkFlow;

use App\Libraries\WorkFlow\WorkFlowEntityPlaceholderInterface;

abstract class WorkFlowPlaceholderAbstract implements WorkFlowEntityPlaceholderInterface
{
    /**
     * entity_id
     *
     * @var undefined|string|int
     */
    protected $entity_id = NULL;

    /**
     * base_entity
     *
     * @var array
     */
    protected $base_entity = [];
    
    /**
     * module_mapping
     *
     * @var array
     */
    protected $module_mapping = [
        'content' => [],
        'sms' => [],
        'email' => []
    ];
    
    /**
     * dependend_classes
     *
     * @var array
     */
    protected $dependend_classes = [];

    public function __construct($entity_id = NULL)
    {
        $this->entity_id = $entity_id;
    }

    public function setModulePlaceholderMapping(array $additional_placeholders = [], $dependent_classes = []): ?array
    {
        $this->setSmsPhoneNumberMapping();
        $this->setEmailAddressMapping();
        $this->setContentMapping();

        if(!empty($additional_placeholders['content'])){
            $this->module_mapping['content'] = array_merge($this->module_mapping['content'], $additional_placeholders['content']);
        }

        if(!empty($additional_placeholders['sms'])){
            $this->module_mapping['sms'] = array_merge($this->module_mapping['sms'], $additional_placeholders['sms']);
        }

        if(!empty($additional_placeholders['email'])){
            $this->module_mapping['email'] = array_merge($this->module_mapping['email'], $additional_placeholders['email']);
        }

        return $this->module_mapping;
        
    }


        /**
     * setContentMapping
     *
     * @return void
     */
    public abstract function setContentMapping(): void;
    
    /**
     * setEmailAddressMapping
     *
     * @return void
     */
    public abstract function setEmailAddressMapping(): void;
    
    /**
     * setSmsPhoneNumberMapping
     *
     * @return void
     */
    public abstract function setSmsPhoneNumberMapping(): void;
    
    /**
     * hasEntityId
     *
     * @return bool
     */
    protected function hasEntityId(): bool {
        return (!empty($this->entity_id));
    }

    /**
     * Get module_mapping
     *
     * @return  array
     */ 
    public function getModule_mapping()
    {
        return $this->module_mapping;
    }
}
