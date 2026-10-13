<?php

namespace Tenant\app\Interfaces;

/**
 * SocialConnectInterface defines the contract for social media connections.
 */
interface SocialConnectInterface
{
    public function getLoginUrl(): string;
    public function unlinkAccount(): array;
    #public function postToSocial(string $id, ?string $message = null, ?string $postImage = null): array;
}