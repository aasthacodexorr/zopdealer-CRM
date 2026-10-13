<?php

namespace Tenant\app\Libraries\SocialConnect;

use Tenant\app\Interfaces\SocialConnectInterface;


/**
 * SocialConnectFactory: Factory for creating social connect objects.
 */
class SocialConnectFactory
{
    private static $classNamespace = "Tenant\app\Libraries\SocialConnect\\";

    /**
     * Create and return an instance of the specified social connect source.
     *
     * @param string $source The name of the social connect source.
     * @return SocialConnectInterface An instance of the specified social connect source.
     * @throws \RuntimeException If the specified source is not supported.
     */

    public static function getInstance(string $source): SocialConnectInterface
    {
        $class = self::$classNamespace . ucfirst($source).'Connect';
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf("Could not find social connect class for source: %s", $source));
        }

        return new $class();
    }
}