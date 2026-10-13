<?php

namespace App\Libraries\WorkFlow;

interface WorkFlowEntityPlaceholderInterface
{
    /**
     * setModulePlaceholderMapping
     *
     * @param  array $additional_placeholders
     * @param  array $dependent_classes
     * @return void
     */
    public function setModulePlaceholderMapping(array $additional_placeholders = [], $dependent_classes = []): ?array;

    /**
     * setEntities
     *
     * @return WorkFlowPlaceholderAbstract
     */
    public function setEntities(): void;
}
