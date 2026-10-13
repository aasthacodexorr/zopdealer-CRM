<?php

namespace Tenant\app\Libraries\ThirdPartyInventory;

interface ThirdPartyAdapterInterface
{    
    /**
     * preprocess
     *
     * @param  array $payload
     * @param  array $optional_data
     * @return array
     */
    public function preprocess(array $payload, array $optional_data = []): array;
}
