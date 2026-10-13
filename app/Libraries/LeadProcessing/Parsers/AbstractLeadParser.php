<?php

namespace App\Libraries\LeadProcessing\Parsers;

use App\Libraries\LeadProcessing\Interfaces\LeadParserInterface;
use App\Libraries\LeadProcessing\DTOs\ParsedLeadData;
use App\Helpers\Utility;

/**
 * Abstract base class for lead parsers with common functionality
 */
abstract class AbstractLeadParser implements LeadParserInterface
{
    protected string $sourceName;

    /**
     * Parse raw lead data into standardized format
     */
    public function parse(string $rawData): ParsedLeadData
    {
        $decodedData = $this->decodeRawData($rawData);
        $extractedData = $this->extractFields($decodedData);
        
        $parsedLead = new ParsedLeadData([
            'source' => $this->getSourceName(),
            'rawData' => $decodedData
        ]);

        $this->mapStandardFields($extractedData, $parsedLead);
        $this->mapAdditionalFields($extractedData, $parsedLead);
        $this->postProcessData($parsedLead);

        return $parsedLead;
    }

    /**
     * Get the source name this parser handles
     */
    public function getSourceName(): string
    {
        return $this->sourceName;
    }

    /**
     * Decode raw JSON data
     */
    protected function decodeRawData(string $rawData): array
    {
        $decoded = json_decode($rawData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
        }

        return $decoded ?? [];
    }

    /**
     * Extract fields from decoded data - to be implemented by concrete parsers
     */
    abstract protected function extractFields(array $decodedData): array;

    /**
     * Map standard fields to ParsedLeadData
     */
    protected function mapStandardFields(array $extractedData, ParsedLeadData $parsedLead): void
    {
        // Map full name
        if (isset($extractedData['full_name'])) {
            $parsedLead->fullName = $extractedData['full_name'];
            // Parse first and last names from full name if they're not already set
            if (empty($parsedLead->firstName) && empty($parsedLead->lastName)) {
                $nameParts = explode(' ', trim($extractedData['full_name']), 2);
                $parsedLead->firstName = $nameParts[0] ?? '';
                $parsedLead->lastName = $nameParts[1] ?? '';
            }
        } elseif (isset($extractedData['first_name']) || isset($extractedData['last_name'])) {
            $parsedLead->firstName = $extractedData['first_name'] ?? '';
            $parsedLead->lastName = $extractedData['last_name'] ?? '';
        }

        // Map email
        if (isset($extractedData['email'])) {
            $parsedLead->email = $this->sanitizeEmail($extractedData['email']);
        }

        // Map phone
        if (isset($extractedData['phone'])) {
            $parsedLead->phone = $this->sanitizePhone($extractedData['phone']);
        }

    }

    /**
     * Map additional fields that are not standard
     */
    protected function mapAdditionalFields(array $extractedData, ParsedLeadData $parsedLead): void
    {
        $standardFields = ['full_name', 'first_name', 'last_name', 'email', 'phone'];
        
        foreach ($extractedData as $key => $value) {
            if (!in_array($key, $standardFields) && !empty($value)) {
                $parsedLead->additionalFields[$key] = $value;
            }
        }
    }

    /**
     * Post-process data after mapping
     */
    protected function postProcessData(ParsedLeadData $parsedLead): void
    {
        // Ensure full name is set
        if (empty($parsedLead->fullName)) {
            $parsedLead->fullName = $parsedLead->getFullName();
        }

        // Clean up additional fields
        $parsedLead->additionalFields = array_filter($parsedLead->additionalFields, function($value) {
            return !empty($value) && $value !== null;
        });
    }

    /**
     * Sanitize email address
     */
    protected function sanitizeEmail(string $email): string
    {
        $email = trim(strtolower($email));
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }

    /**
     * Sanitize phone number
     */
    protected function sanitizePhone(string $phone): string
    {
        return Utility::formatPhoneNumber($phone);
    }

    /**
     * Normalize field name for consistent mapping
     */
    protected function normalizeFieldName(string $fieldName): string
    {
        return strtolower(str_replace([' ', '-', '_'], '_', trim($fieldName)));
    }
}
