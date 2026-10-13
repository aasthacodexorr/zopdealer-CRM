<?php

namespace App\Policies;

use Exception;
use App\Libraries\Oauth2 as Oauth;

class AuthPolicy
{

    public $role;
    public $organization;
    public $email;
    public $id;
    public $current_organization;

    public function checkAuthentication()
    {
        try {
            $authorization = apache_request_headers()["Authorization"];
            $authorization = explode(" ", $authorization);
            $id_token_validation = Oauth::token_validation($authorization[1], 'RS256');
            $id_token_validation = json_decode($id_token_validation);
            $this->email = $id_token_validation->email;
            $metadata  = $id_token_validation->app_metadata;
            $this->organization = $metadata->organization;
            $this->role = $metadata->role;
            $this->id = $metadata->id;

            $organization = '';
            if (isset($_SERVER['HTTP_HOST'])) {
                $urlParts = explode('.', $_SERVER['HTTP_HOST']);
                $count = count($urlParts);
                if ($count > 2 && $urlParts[0] !== "www") {
                    $organization = $urlParts[0];
                    $this->current_organization = $organization;
                }
            }

            if (property_exists($id_token_validation, "error")) {
                throw new Exception('Unauthorized Request');
            }

            if (!property_exists($id_token_validation, "email")) {
                throw new Exception('Unauthorized Request');
            }

            /**
             * If the user doesn't belongs to zopdealer or the ogranization throw unauthorized request.
             */

            if ($metadata->organization != ORGANIZATION && $metadata->organization != $organization) {
                throw new Exception("Unauthorized Request");
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            die(json_encode(['error' => 'Unauthorized Request']));
        }
    }

    public function checkIfUserIsActive()
    {
        try {

            if ($this->organization == ORGANIZATION && strtolower($this->role)  == strtolower('Super Owner')) {
            } else {
                $db = \Config\Database::connect();
                $builder = $db->table('user_metadata');
                $builder->join('users', 'users.id = user_metadata.user_id', 'inner');
                $builder->where('users.email', $this->email);
                $result = $builder->get()->getResult();
                if ((int) $result[0]->is_active !== 1) {
                    throw new Exception("Unauthorized Request");
                }
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            die(json_encode(['error' => 'Unauthorized Request']));
        }
    }

    /**
     * check this function checks if the user is authorized.
     *
     * @param  mixed $regex This should be resource-crud Example : Inventory-crud
     * @return void
     */
    public function check($regex)
    {
        $resource =  explode("-", $regex)[0];
        $right = explode("-", $regex)[1];

        /**
         * Check If the employee is from zopdealer and if they are super owner.
         */

        if ($this->organization == ORGANIZATION && strtolower($this->role)  == strtolower('Super Owner')) {
            //Continue
        } else if ($this->organization == ORGANIZATION && strtolower($this->role)  != strtolower('Super Owner')) {
            //Doesn't allow to delete the resources to anyone expect Super Owner From zopdealer.
            if ($right == 'delete') {
                header('HTTP/1.1 401 Unauthorized', true, 401);
                die(json_encode(['error' => 'Unauthorized Request']));
            }
        } else {
            // Check if the rights are available for the role.
            $db = \Config\Database::connect();
            $builder = $db->table('resource_role');
            $builder->select('resource_role.' . $right);
            $builder->join('roles', 'resource_role.role_id = roles.id', 'inner');
            $builder->join('resources', 'resource_role.resource_id = resources.id', 'inner');
            $builder->join('user_role', 'user_role.role_id = roles.id', 'inner');
            $builder->join('users', 'users.id = user_role.user_id', 'inner');
            $builder->where('users.email', $this->email);
            $builder->where('resources.name', $resource);
            $result = $builder->get()->getResult();
            $saved_right = $result[0]->{$right};
            if (count($result) < 1 || $saved_right != 1) {
                header('HTTP/1.1 401 Unauthorized', true, 401);
                die(json_encode(['error' => 'Unauthorized Request']));
            }
        }
    }

    /**
     * checkSuperOwner This returns whether the user is super owner 
     *
     * @return bool
     */
    public function isSuperOwner(): bool
    {
        return $this->organization == ORGANIZATION && strtolower($this->role)  == strtolower('Super Owner');
    }

    /**
     * checkOwner This returns whether the user is owner or not
     *
     * @return bool
     */
    public function hasOwnerRole(): bool
    {
        return strtolower($this->role)  == strtolower('Owner');
    }

    /**
     * hasManagerRole This returns whether the user is manager or not
     *
     * @return bool
     */
    public function hasManagerRole(): bool
    {
        return strtolower($this->role)  == strtolower('Manager');
    }

    /**
     * resourceOwner. This function compares if the resource belongs to particular id.
     *
     * @return bool
     */
    public function isResourceOwner($id): bool
    {
        return $this->id == $id;
    }

    /**
     * hasElevatedRoles
     *
     * @return bool
     */
    public function hasElevatedRoles()
    {
        return ($this->hasManagerRole() || $this->hasOwnerRole() || $this->isSuperOwner());
    }

    /**
     * isTenant
     *
     * @return void
     */
    public function isTenant()
    {
        return $this->organization !== ORGANIZATION;
    }

    /**
     * Get the value of current_organization
     */
    public function getCurrentOrganization()
    {
        return $this->current_organization;
    }

    /**
     * currentUserID
     *
     * @return
     */
    public function currentUserID()
    {
        return $this->id;
    }

    public function currentUserEmail()
    {
        return $this->email;
    }
}
