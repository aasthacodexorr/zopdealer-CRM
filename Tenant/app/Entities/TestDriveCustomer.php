<?php

namespace Tenant\App\Entities;

use CodeIgniter\Entity;

class TestDriveCustomer extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'last_name' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'driver_license' => 'string',
        'license_expiry' => 'string',
        'license_front' => 'string',
        'license_back' => 'string',
        'street_address' => 'string',
        'apt' => 'string',
        'city' => 'string',
        'search_keyword'  => 'string',
        'province' => 'string',
        'postal_code' => 'string',
        'country' => 'string'
    ];

    /**
     * Get full name
     */
    public function getFullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get full address
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->street_address,
            $this->apt ? 'Apt ' . $this->apt : null,
            $this->city,
            $this->province,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Check if customer has complete address
     */
    public function hasCompleteAddress(): bool
    {
        return !empty($this->street_address) 
            && !empty($this->city) 
            && !empty($this->province) 
            && !empty($this->postal_code);
    }

    /**
     * Check if customer has driver license
     */
    public function hasDriverLicense(): bool
    {
        return !empty($this->driver_license);
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhone(): ?string
    {
        if (empty($this->phone)) {
            return null;
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        // Format as (XXX) XXX-XXXX for 10 digit numbers
        if (strlen($phone) === 10) {
            return sprintf('(%s) %s-%s', 
                substr($phone, 0, 3),
                substr($phone, 3, 3),
                substr($phone, 6, 4)
            );
        }

        return $this->phone;
    }
}

