<?php
namespace Tenant\app\Libraries\SocialConnect;

/**
 * Abstract SocialConnect class provides common functionality for social media connections.
 */
abstract class SocialConnectAbstract implements \Tenant\app\Interfaces\SocialConnectInterface
{
    protected string $callbackUrl;
    protected array $errors = [
        'ACCOUNT_UNLINKED' => 'Please link your social account.',
        'FAILURE' => 'Something went wrong. Please contact admin.',
    ];

    /**
     * Get the social configuration
     */
    //abstract public function getSocialConfiguration(array $fields = []): array;


    /**
     * Gets the access token from the database.
     */
    abstract public function getAccessTokenFromDb(): ?string;


    abstract public function postToSocialMedia(?string  $message, array $imagePaths, array $extra):  ?array;

}