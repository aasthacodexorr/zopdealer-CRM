<?php

namespace Tenant\app\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table      = 'locations';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'street',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'email',
        'status',
        'default',
        'dealer_registration_number',
        'tax_number',
        'rin_number',
        'fax',
        'created_at',
        'updated_at',
        'deleted_at',
        'business_name',
        'show_on_website',
        'address_bar',
        'address_map_url'

    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'Tenant\app\Entities\Location';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'                          => [
            "label" => 'Name',
            "rules" => 'required|alpha_numeric_punct|min_length[2]'
        ],
        'business_name'                          => [
            "label" => 'Business Name',
            "rules" => 'required|alpha_numeric_punct|min_length[2]'
        ],
        'street'                        => [
            "label" => 'Street',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'city'                          => [
            "label" => 'City',
            "rules" => 'required|alpha_numeric_punct|min_length[2]'
        ],
        'state'                         => [
            "label" => 'State',
            "rules" => 'required|alpha_numeric_punct|min_length[2]'
        ],
        'zipcode'                           => [
            "label" => 'Zip Code',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'country'                         => [
            "label" => 'State',
            "rules" => 'required|alpha_numeric_punct|min_length[2]'
        ],
        'phone'  => [
            "label" => 'Phone',
            "rules" => 'permit_empty|is_natural|exact_length[10]'
        ],
        'fax'  => [
            "label" => 'Fax',
            "rules" => 'permit_empty|is_natural'
        ],
        'email'  => [
            "label" => 'Email',
            "rules" => 'permit_empty|valid_email'
        ],
        'dealer_registration_number'  => [
            "label" => 'Dealer Registration Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'tax_number'  => [
            "label" => 'Tax Number',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'rin_number'  => [
            "label" => 'RIN',
            "rules" => 'permit_empty|alpha_numeric_punct'
        ],
        'default'  => [
            "label" => 'Default',
            "rules" => 'permit_empty|is_natural'
        ],
        'show_on_website'  => [
            "label" => 'Show on Website',
            "rules" => 'permit_empty|is_natural'
        ],
        'address_bar'  => [
            "label" => 'Address Bar Map URL',
            "rules" => 'permit_empty'
        ],
        'address_map_url'  => [
            "label" => 'Address Map Iframe URL',
            "rules" => 'permit_empty'
        ],

    ];
}
