<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table      = 'clients';
    protected $primaryKey = 'client_id';
    protected $allowedFields = [
        'name', 'street', 'city', 'state',
        'zip', 'email', 'telephone', 'alternate_telephone',
        'subdomain', 'website', 'dealer_registration_number', 'gst_number',
        'hst_number', 'client_hash', 'connection_group', 'slide_limit', 'gallery_limit',
        'blog_limit', 'adf_email',
        'review_limit', 'team_limit', 'created_at', 'updated_at', 'deleted_at', 'car_history_schedule',
        'fbmp_status_update_schedule', 'craigslist_status_update_schedule', 'image_overlay_schedule',
        'client_cleanup_schedule', 'download_third_party_image_schedule', 'update_all_index_schedule',
        'external_review_schedule', 'lead_response_encryption_schedule'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\Client';
    protected $useTimestamps = true;

    protected $validationRules    = [
        'name'                          => [
            "label" => 'Name',
            "rules" => 'required|alpha_numeric_space|min_length[3]'
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
        'zip'                           => [
            "label" => 'Zip Code',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'email'                         => [
            "label" => 'Email Address',
            "rules" => 'required|valid_email'
        ],
        'adf_email'                         => [
            "label" => 'ADF Email',
            "rules" => 'permit_empty|valid_email'
        ],
        'subdomain'                     => [
            "label" => 'Subdomain',
            "rules" => 'required|alpha_dash|min_length[3]|is_unique[clients.subdomain]'
        ],
        'slide_limit'                   => [
            "label" => 'Slider Limit',
            "rules" => 'greater_than_equal_to[0]'
        ],
        'gallery_limit'                 => [
            "label" => 'Gallery Limit',
            "rules" => 'greater_than_equal_to[0]'
        ],
        'blog_limit'                    => [
            "label" => 'Blog Limit',
            "rules" => 'greater_than_equal_to[0]'
        ],
        'review_limit'                  => [
            "label" => 'Review Limit',
            "rules" => 'greater_than_equal_to[0]'
        ],
        'team_limit'                    => [
            "label" => 'Team Limit',
            "rules" => 'greater_than_equal_to[0]'
        ],
        'connection_group'                         => [
            "label" => 'Connection Group',
            "rules" => 'required|alpha_dash|min_length[2]'
        ],
        'car_history_schedule'  => [
            "label" => 'Car History Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'fbmp_status_update_schedule'  => [
            "label" => 'FBMP Status Update Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'craigslist_status_update_schedule'  => [
            "label" => 'Craigslist Status Update Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'image_overlay_schedule'  => [
            "label" => 'Image Overlay Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'client_cleanup_schedule'  => [
            "label" => 'Client Clean Up Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'download_third_party_image_schedule'  => [
            "label" => 'Download Third Party Image Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'update_all_index_schedule'  => [
            "label" => 'Update All Index Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'external_review_schedule'  => [
            "label" => 'External Review Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'update_inventory_typesense_index_schedule'  => [
            "label" => 'Update Inventory Typesense Index Schedule',
            "rules" => 'permit_empty|valid_date'
        ],
        'lead_response_encryption_schedule'  => [
            "label" => 'Lead Response Encryption Schedule',
            "rules" => 'permit_empty|valid_date'
        ],

    ];
}
