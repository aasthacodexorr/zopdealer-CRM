<?php

namespace App\Libraries\LeadProcessing\Services;

use App\Libraries\LeadProcessing\DTOs\ParsedLeadData;
use App\Libraries\LeadProcessing\Services\LeadParserFactory;
use App\Libraries\LeadProcessing\Exceptions\LeadProcessingException;

/**
 * Main service for processing leads from various sources
 */
class LeadProcessingService
{

    /**
     * Process lead data from specified source
     *
     * @param string $rawData The raw JSON lead data
     * @param string $source The lead source name (required)
     * @return ParsedLeadData The parsed and validated lead data
     * @throws LeadProcessingException When processing fails
     */
    public function processLead(string $rawData, string $source): ParsedLeadData
    {
        if (empty($source)) {
            throw new LeadProcessingException(
                "Source parameter is required",
                ['data_length' => strlen($rawData)]
            );
        }

        try {
            // Get parser for the specified source
            $parser = LeadParserFactory::getParser($source);

            // Parse the data
            $parsedLead = $parser->parse($rawData);

            // Validate parsed data
            if (!$parsedLead->isValid()) {
                throw new LeadProcessingException(
                    "Parsed lead data is invalid: " . implode(', ', $parsedLead->getValidationErrors()),
                    ['source' => $parser->getSourceName(), 'errors' => $parsedLead->getValidationErrors()]
                );
            }

            return $parsedLead;

        } catch (LeadProcessingException $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            throw new LeadProcessingException(
                $e->getMessage(),
                ['source' => $source, 'data_length' => strlen($rawData)]
            );
        } catch (\Exception $e) {
            throw new LeadProcessingException(
                "Error processing lead: " . $e->getMessage(),
                ['source' => $source, 'original_error' => $e->getMessage()]
            );
        }
    }

}
