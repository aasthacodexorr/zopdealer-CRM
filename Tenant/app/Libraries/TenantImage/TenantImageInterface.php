<?php

namespace Tenant\app\Libraries\TenantImage;

/***
 * TenantImageInterface defines the structure of the Tenant Image Types
 */
interface TenantImageInterface
{
    public function index(): ?array;

    public function postCreateProcessing(array $data = []): ?array;

    public function delete($id): ?array;

    public function changeActivation($id, $activation_value): ?array;

    public function getActiveImages(): ?array;
}
