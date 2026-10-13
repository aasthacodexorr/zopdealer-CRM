<?php

namespace App\Libraries\LeadProcessing\Parsers;

use App\Libraries\LeadProcessing\Parsers\AbstractLeadParser;

/**
 * Parser for Google lead data
 *
 */
class GoogleLeadParser extends AbstractLeadParser
{
    protected string $sourceName = 'google';

    /**
     * Extract fields from Google lead data
     */
    protected function extractFields(array $decodedData): array
    {
        $extractedData = [];

        if (!is_array($decodedData)) {
            return $extractedData;
        }

        foreach ($decodedData as $field) {
            if (!isset($field['column_id']) || !isset($field['string_value'])) {
                continue;
            }

            $columnId = strtoupper(trim($field['column_id']));
            $stringValue = trim($field['string_value']);

            if (!empty($stringValue)) {
                $extractedData[$columnId] = $stringValue;
            }
        }

        // Map Google-specific field names to standard names
        $extractedData = $this->mapGoogleFields($extractedData);

        return $extractedData;
    }

    /**
     * Map Google-specific field names to standard field names
     */
    protected function mapGoogleFields(array $extractedData): array
    {
        $fieldMapping = [
            'FULL_NAME' => 'full_name',
            'EMAIL' => 'email',
            'PHONE_NUMBER' => 'phone',
            'COMPANY_NAME' => 'company_name',
            'JOB_TITLE' => 'job_title',
            'CITY' => 'city',
            'STATE' => 'state',
            'COUNTRY' => 'country',
            'FIRST_NAME' => 'first_name',
            'LAST_NAME' => 'last_name'
        ];

        $mappedData = [];
        
        foreach ($extractedData as $key => $value) {
            $mappedKey = $fieldMapping[$key] ?? strtolower($key);
            $mappedData[$mappedKey] = $value;
        }

        return $mappedData;
    }
}
