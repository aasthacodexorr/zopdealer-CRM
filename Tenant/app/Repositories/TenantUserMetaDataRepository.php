<?php

namespace Tenant\app\Repositories;

use App\Models\UserModel;
use App\Repositories\BaseRepository;
use Tenant\app\Models\TenantUserModel;
use Tenant\app\Entities\Inventory;

class TenantUserMetaDataRepository extends BaseRepository
{

    private $user;

    public function __construct()
    {
        parent::__construct(new TenantUserModel());
    }

    public function setTenantUserModel($email)
    {
        $this->user = $this->model->find($email);
    }

    public function userMetaData()
    {
        $builder = $this->model->builder();
        $builder->select('user_metadata.*');
        $builder->join('user_metadata', 'user_metadata.user_id = users.id');
        $builder->where('users.email', $this->user->email);
        $builder->limit(1);
        return $builder->get()->getResult();
    }

    public function setUserMetaData($data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_metadata');
        $builder->where('user_id', (int) $this->user->id);
        $builder->update($data);
    }

    public function updateUserMetaDataByID($id, $data)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_metadata');
        $builder->where('user_id', (int) $id);
        $builder->update($data);
    }

    public function updateRoleByUserID($user_id, $role_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_role');
        $builder->where('user_id', (int) $user_id);
        $builder->update(['role_id' => (int) $role_id]);
    }

    public function findAllUsers(array $columns)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_metadata');
        $builder->join('user_role', 'user_metadata.user_id = user_role.user_id', 'inner');
        $builder->join('roles', 'user_role.role_id = roles.id', 'inner');
        $builder->join('users', 'user_metadata.user_id = users.id', 'inner');
        $builder->select($columns);
        return $builder->get()->getResult();
    }

    public function getUserMetaDataById($id)
    {
        $builder = $this->model->builder();
        $builder->select('user_metadata.*');
        $builder->join('user_metadata', 'user_metadata.user_id = users.id');
        $builder->where('users.id', $id);
        $builder->limit(1);
        return  $builder->get()->getResultArray();
    }

    public function getAllUserRoles()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('roles');
        $builder->select("*");
        return $builder->get()->getResult();
    }

    public function deleteUser($user_id): array
    {
        try {
            $db = \Config\Database::connect();
            $db->query("DELETE from user_role WHERE user_id= (SELECT id from users WHERE id = " . $user_id . ") LIMIT 1 ;");
            $db->query("DELETE from user_metadata WHERE user_id= (SELECT id from users WHERE id = " . $user_id . ") LIMIT 1 ;");
            $db->query("DELETE from users where id = " . $user_id . " LIMIT 1 ;");

            return ["success" => "User deleted with id " . $user_id];
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }
    }

    /**
     * checkUserAuthentication
     *
     * @param  string|int $user_id
     * @param  string $password
     * @return bool
     */
    public function checkUserAuthentication(string $user_id, string $password): bool
    {
        $user = $this->getUserEntityWithoutMetaDataById($user_id);
        if (!empty($user)) {
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }

        return false;
    }

    public function getUserEntityWithoutMetaDataById($id)
    {
        $this->setModel(new UserModel());
        return $this->model->asArray()->find($id);
    }
}
