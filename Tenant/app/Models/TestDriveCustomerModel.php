<?php

namespace Tenant\app\Models;

use App\Models\BaseSecureModel;

class TestDriveCustomerModel extends BaseSecureModel
{
    protected $table      = 'test_drives_customers';
    protected $primaryKey = 'id';

    protected $searchableColumns = [
        'first_name', 'last_name', 'phone', 'email'
    ];
    /**
     * Fields that should be encrypted
     */
    protected $secureFields = ['driver_license'];

    protected $allowedFields = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'driver_license',
        'license_expiry',
        'license_front',
        'license_back',
        'street_address',
        'apt',
        'city',
        'province',
        'postal_code',
        'country',
        'search_keyword',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\TestDriveCustomer';
    protected $useTimestamps = true;

    // Encrypt fields before insert/update
    protected $beforeInsert = ['encryptFields'];
    protected $beforeUpdate = ['encryptFields'];
    
    // Decrypt fields after find
    protected $afterFind = ['decryptFields'];

    protected $validationRules = [
        'first_name' => [
            'label' => 'First Name',
            'rules' => 'required|max_length[255]'
        ],
        'last_name' => [
            'label' => 'Last Name',
            'rules' => 'required|max_length[255]'
        ],
        'phone' => [
            'label' => 'Phone',
            'rules' => 'required|max_length[50]'
        ],
        'email' => [
            'label' => 'Email',
            'rules' => 'permit_empty|valid_email|max_length[255]'
        ],
        'driver_license' => [
            'label' => 'Driver License',
            'rules' => 'required'
        ],
        'license_expiry' => [
            'label' => 'License Expiry',
            'rules' => 'required'
        ],
        'license_front' => [
            'label' => 'License Front',
            'rules' => 'required|max_length[255]'
        ],
        'license_back' => [
            'label' => 'License Back',
            'rules' => 'required|max_length[255]'
        ],
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required'
        ],
        'last_name' => [
            'required' => 'Last name is required'
        ],
        'email' => [
            'valid_email' => 'Please provide a valid email address'
        ],
        'driver_license' => [
            'required' => 'Driver License is required.'
        ],
        'phone' => [
            'required' => 'Phone number is required.'
        ],
        'license_expiry' => [
            'required' => 'Driver License expiry date  is required in format (YYYY-MM-DD).',
        ],
        'license_front' => [
            'required' => 'Driver License front image  is required.'
        ],
        'license_back' => [
            'required' => 'Driver License back image is required.'
        ]
    ];

}

