<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class BaseSecureModel extends  ExactSearchableModel
{
    protected $encrypter;
    
    /**
     * Fields that should be encrypted/decrypted
     * Override this in child classes
     */
    protected $secureFields = [];

    public function __construct()
    {
        parent::__construct();
        $this->encrypter = Services::encrypter();
    }

    /**
     * Encrypt value (base64 safe)
     */
    protected function encryptValue($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return base64_encode(
            $this->encrypter->encrypt($value)
        );
    }

    /**
     * Decrypt value (base64 safe)
     */
    protected function decryptValue($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        try {
            return $this->encrypter->decrypt(
                base64_decode($value)
            );
        } catch (\Exception $e) {
            // If decryption fails, return original value
            return $value;
        }
    }

    /**
     * Encrypt selected fields before save
     */
    protected function encryptFields(array $data)
    {
        if (!isset($this->secureFields) || empty($this->secureFields)) {
            return $data;
        }

        foreach ($this->secureFields as $field) {
            if (isset($data['data'][$field])) {
                $data['data'][$field] = $this->encryptValue($data['data'][$field]);
            }
        }

        return $data;
    }

    /**
     * Decrypt selected fields after fetch
     */
    protected function decryptFields(array $data)
    {
        if (!isset($this->secureFields) || empty($this->secureFields)) {
            return $data;
        }

        if (isset($data['data'])) {
            // Check if it's multiple rows or single row
            if (is_array($data['data']) && isset($data['data'][0])) {
                // Multiple rows
                foreach ($data['data'] as &$row) {
                    $this->decryptRow($row);
                }
            } else {
                // Single row
                $this->decryptRow($data['data']);
            }
        }

        return $data;
    }

    /**
     * Decrypt a single row (handles both arrays and entities)
     */
    protected function decryptRow(&$row)
    {
        if (is_object($row)) {
            // Handle Entity objects
            foreach ($this->secureFields as $field) {
                if (isset($row->$field)) {
                    $row->$field = $this->decryptValue($row->$field);
                }
            }
        } elseif (is_array($row)) {
            // Handle arrays
            foreach ($this->secureFields as $field) {
                if (isset($row[$field])) {
                    $row[$field] = $this->decryptValue($row[$field]);
                }
            }
        }
    }
}

