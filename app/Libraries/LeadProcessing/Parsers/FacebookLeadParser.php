<?php

namespace App\Libraries\LeadProcessing\Parsers;

use App\Libraries\LeadProcessing\Parsers\AbstractLeadParser;

/**
 * Parser for Facebook lead data
 *
 */
class FacebookLeadParser extends AbstractLeadParser
{
    protected string $sourceName = 'facebook';

    /**
     * Extract fields from Facebook lead data
     */
    protected function extractFields(array $decodedData): array
    {
        $extractedData = [];

        if (!is_array($decodedData)) {
            return $extractedData;
        }

        foreach ($decodedData as $field) {
            if (!isset($field['name']) || !isset($field['values'])) {
                continue;
            }

            $fieldName = $this->normalizeFieldName($field['name']);
            $fieldValue = $field['values'][0] ?? '';

            if (!empty($fieldValue)) {
                $extractedData[$fieldName] = trim($fieldValue);
            }
        }

        // Map Facebook-specific field names to standard names
        $extractedData = $this->mapFacebookFields($extractedData);

        return $extractedData;
    }

    /**
     * Map Facebook-specific field names to standard field names
     */
    protected function mapFacebookFields(array $extractedData): array
    {
        $fieldMapping = [
            'phone_number' => 'phone',
            'email_address' => 'email',
            'user_email' => 'email',
            'user_phone' => 'phone',
            'company' => 'company_name',
            'business_name' => 'company_name',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'name' => 'full_name'
        ];

        $mappedData = [];
        
        foreach ($extractedData as $key => $value) {
            $mappedKey = $fieldMapping[$key] ?? $key;
            $mappedData[$mappedKey] = $value;
        }

        return $mappedData;
    }


}
