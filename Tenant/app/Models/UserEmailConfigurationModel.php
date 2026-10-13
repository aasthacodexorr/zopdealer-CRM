<?php

namespace Tenant\app\Models;

use App\Helpers\Utility;
use CodeIgniter\Model;

class UserEmailConfigurationModel extends Model
{
    protected $table      = 'user_email_configuration';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'name', 'email', 'password', 'auth',
        'outgoing_hostname', 'outgoing_port', 'outgoing_secure',
        'incoming_hostname', 'incoming_port', 'incoming_secure',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Tenant\app\Entities\TenantUser';
    protected $useTimestamps = true;

    protected $beforeInsert = ['encryptPassword'];
    protected $beforeUpdate = ['encryptPassword'];

    protected $afterFind = ['decryptPassword'];


    protected $validationRules    = [
        'user_id' => [
            "label" => 'User Id',
            "rules" => 'required|is_unique[user_email_configuration.user_id,id,{id}]'
        ],
        'name' => [
            "label" => 'Name',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'email' => [
            "label" => 'Email',
            "rules" => 'required|valid_email'
        ],
        'password' => [
            "label" => 'Password',
            "rules" => 'required'
        ],
        'auth'  => [
            "label" => 'Authentication Flag',
            "rules" => 'permit_empty|is_natural'
        ],
        'outgoing_hostname' => [
            "label" => 'Outgoing Hostname',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'outgoing_port' => [
            "label" => 'Outgoing Port',
            "rules" => 'required|is_natural'
        ],
        'outgoing_secure' => [
            "label" => 'Outgoing Security Protocol',
            "rules" => 'required|alpha'
        ],

        'incoming_hostname' => [
            "label" => 'Incoming Hostname',
            "rules" => 'required|alpha_numeric_punct'
        ],
        'incoming_port' => [
            "label" => 'Incoming Port',
            "rules" => 'required|is_natural'
        ],
        'incoming_secure' => [
            "label" => 'Incoming Security Protocol',
            "rules" => 'required|alpha'
        ],
    ];


    protected function encryptPassword(array $data)
    {
        if (!isset($data['data']['password'])) return $data;

        $data['data']['password'] = Utility::encryptString($data['data']['password'], getenv("password.encryption"));
        return $data;
    }

    protected function decryptPassword(array $data)
    {
        if (!isset($data['data'])) return null;

        if (gettype($data['data']) == 'array')
            $data['data']['password'] = Utility::decryptString($data['data']['password'], getenv("password.encryption"));
        return $data;
    }
}
