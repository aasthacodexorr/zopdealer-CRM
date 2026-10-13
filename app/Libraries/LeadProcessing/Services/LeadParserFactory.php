<?php

namespace App\Libraries\LeadProcessing\Services;

use App\Libraries\LeadProcessing\Interfaces\LeadParserInterface;

/**
 * Simple factory for creating lead parsers based on source name
 * Dynamically loads parser classes based on naming convention
 */
class LeadParserFactory
{
    private static array $parsers = [];

    /**
     * Get parser by source name
     *
     * @param string $source The lead source name (e.g., 'facebook', 'google')
     * @return LeadParserInterface The parser instance
     * @throws \InvalidArgumentException When parser class doesn't exist or is invalid
     */
    public static function getParser(string $source): LeadParserInterface
    {
        $source = strtolower(trim($source));

        if (empty($source)) {
            throw new \InvalidArgumentException("Source name cannot be empty");
        }

        // Use cached parser if available
        if (isset(self::$parsers[$source])) {
            return self::$parsers[$source];
        }

        // Build parser class name based on convention
        $className = 'App\\Libraries\\LeadProcessing\\Parsers\\' . ucfirst($source) . 'LeadParser';

        // Check if class exists
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Parser class '{$className}' not found for source '{$source}'");
        }

        // Create parser instance
        $parser = new $className();

        // Validate parser implements interface
        if (!$parser instanceof LeadParserInterface) {
            throw new \InvalidArgumentException("Parser class '{$className}' must implement LeadParserInterface");
        }

        // Cache and return parser
        self::$parsers[$source] = $parser;
        return $parser;
    }

}
