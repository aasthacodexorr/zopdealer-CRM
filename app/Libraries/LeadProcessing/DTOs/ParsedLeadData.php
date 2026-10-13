<?php

namespace App\Libraries\LeadProcessing\DTOs;

/**
 * Data Transfer Object for standardized lead data
 */
class ParsedLeadData
{
    public string $firstName = '';
    public string $lastName = '';
    public string $fullName = '';
    public string $email = '';
    public string $phone = '';
    public string $companyName = '';
    public array $additionalFields = [];
    public array $rawData = [];
    public string $source = '';

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Get full name, constructing it from first/last if needed
     */
    public function getFullName(): string
    {
        if (!empty($this->fullName)) {
            return $this->fullName;
        }

        return trim($this->firstName . ' ' . $this->lastName);
    }

    /**
     * Get validation errors
     */
    public function getValidationErrors(): array
    {
        $errors = [];

        if (empty($this->getFullName())) {
            $errors[] = 'Full name is required';
        }

        if (empty($this->email) && empty($this->phone)) {
            $errors[] = 'Either email or phone is required';
        }

        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        return $errors;
    }

    /**
     * Check if data is valid
     */
    public function isValid(): bool
    {
        return empty($this->getValidationErrors());
    }
}
