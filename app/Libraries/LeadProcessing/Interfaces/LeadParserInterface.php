<?php

namespace App\Libraries\LeadProcessing\Interfaces;

use App\Libraries\LeadProcessing\DTOs\ParsedLeadData;

/**
 * Interface for lead parsers that handle different lead sources
 */
interface LeadParserInterface
{
    /**
     * Parse raw lead data into standardized format
     *
     * @param string $rawData JSON string from lead source
     * @return ParsedLeadData Standardized lead data
     */
    public function parse(string $rawData): ParsedLeadData;

    /**
     * Get the source name this parser handles
     *
     * @return string Source name (e.g., 'facebook', 'google')
     */
    public function getSourceName(): string;

}
