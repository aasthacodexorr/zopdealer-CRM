<?php

namespace Tenant\app\Libraries\ExternalReview;

use Tenant\app\Interfaces\ExternalReviewInterface;

/**
 * ExternalReviewFactory: Factory for creating external review objects.
 */
class ExternalReviewFactory
{
    private static $classNamespace = "Tenant\app\Libraries\ExternalReview\\";

    /**
     * Create and return an instance of the specified external review source.
     *
     * @param string $source The name of the external review source.
     * @return ExternalReviewInterface An instance of the specified external review source.
     * @throws \InvalidArgumentException If the specified source is not supported.
     */

    public static function getInstance(string $source): ExternalReviewInterface
    {
        $class = self::$classNamespace . ucfirst($source).'Review';
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf("Could not find external review class for source: %s", $source));
        }

        return new $class();
    }
}