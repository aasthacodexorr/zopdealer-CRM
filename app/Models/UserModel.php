<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel This model is related to the user data that is stored in database.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'emai', 'password',
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $useSoftDeletes = true;
    protected $returnType    = 'App\Entities\User';
    protected $useTimestamps = true;

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = crypt($data['data']['password'] , '$2a$10$'.bin2hex(random_bytes(22)).'$');

        return $data;
    }
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected $validationRules    = [
        'password'  => [
            "label" => 'Name',
            "rules" => 'required|min_length[6]'
        ],
        'email' => [
            "label" => 'Email',
            "rules" => 'required|valid_email|is_unique[users.email]',
        ]
    ];
}
