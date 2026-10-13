<?php

namespace App\Controllers;

use App\Entities\Client;
use App\Helpers\Utility;
use App\Models\ClientModel;
use App\Repositories\ClientRepository;

/**
 * ClientController : REST API Controller for the client business.
 */
class ClientController extends BaseController
{


    /**
     * create new client for a business
     *
     * @return void
     */
    public function create()
    {
        $data = $this->request->getPost();
        $data['client_hash'] = Utility::uniqueNumberBasedString('ZD');
        $client = new Client($data);

        $clientModel = new ClientModel();

        if ($clientModel->save($client) === false) {
            $this->response->setStatusCode('400')->setContentType('application/json')->setJSON($clientModel->errors())->send();
            exit;
        } else {
            /* If successful create the database for the new client and switch to it */
            $client_id = $clientModel->insertID;
            $forge = \Config\Database::forge();
            $forge->createDatabase($data['subdomain'] . '_' . $data['client_hash'], TRUE);
            $db = \Config\Database::connect();
            $db->setDatabase($data['subdomain'] . '_' . $data['client_hash']);
            try {

                exec("mkdir -m 777 " . ROOTPATH . "/public/upload/" . md5($data['subdomain'] . '_' . $data['client_hash']));
                exec("mkdir -m 777 " . ROOTPATH . "/public/upload/" . md5($data['subdomain'] . '_' . $data['client_hash']) . '/overlay');
                exec("mkdir -m 777 " . ROOTPATH . "/public/image/" . md5($data['subdomain'] . '_' . $data['client_hash']));

                $migrate = \Config\Services::migrations(null, $db, false);
                $common_migrations = $migrate->findMigrations('Tenant/app');

                foreach ($common_migrations as $migration) {
                    if (strpos($migration->path, 'Common') !== false) {
                        $migrate->force($migration->path, $migration->class, null);
                    }
                }

                $migrate->setNamespace('Tenant/app')->latest();



                /**
                 * TODO: New Details Save In The Database 
                 */

                // create role
                $builder = $db->table('roles');
                $builder->insertBatch([
                    [
                        "name" => "Owner",
                        "title" => "Role of owner allows the ability to control the access rights for other users.",
                    ],
                    [
                        "name" => "Manager",
                        "title" => "Role of manager allows the ability to control most of the visits.",
                    ],
                    [
                        "name" => "Staff",
                        "title" => "Rights and Access assigned by the owner.",
                    ]
                ]);

                // create resource
                $builder = $db->table('resources');
                $builder->insertBatch([
                    [
                        "name" => "Inventory",
                        "title" => "Inventory Module.",
                    ],
                    [
                        "name" => "Syndication",
                        "title" => "Syndication Module.",
                    ],
                    [
                        "name" => "User",
                        "title" => "User Managment.",
                    ]
                ]);

                // map resource to role
                $db->table('resource_role')->insertBatch([
                    ["resource_id" => 1, "role_id" => 1, "create" => 1, "read" => 1, "update" => 1, "delete" => 1],
                    ["resource_id" => 1, "role_id" => 2, "create" => 1, "read" => 1, "update" => 1, "delete" => 1],
                    ["resource_id" => 1, "role_id" => 3, "create" => 1, "read" => 1, "update" => 1, "delete" => 1],
                    ["resource_id" => 3, "role_id" => 1, "create" => 1, "read" => 1, "update" => 1, "delete" => 1],
                    ["resource_id" => 3, "role_id" => 2, "create" => 1, "read" => 1, "update" => 1, "delete" => 0],
                    ["resource_id" => 3, "role_id" => 3, "create" => 0, "read" => 0, "update" => 0, "delete" => 0],

                ]);

                // map role to the user


                //name of the application based on the subdomain by default
                $db->table('app')->insert(["name" => $data["subdomain"]]);

                //user and password
                $password = $data["password"] == "" ? 'password' : $data["password"];
                $password = crypt($password, '$2a$10$' . bin2hex(random_bytes(22)) . '$');

                $db->table('users')->insert(["email" => $data["email"], "password" => $password]);
                $db->table('user_role')->insert(["user_id" => 1, "role_id" => 1]);
                $db->table('user_metadata')->insert(["user_id" => 1, "inventory_view" => 1]);


                $cache = \Config\Services::cache();
                if ($cache->isSupported()) {
                    $cache->save(
                        $data["subdomain"],
                        [
                            "database" => $data['subdomain'] . '_' . $data['client_hash'],
                            "group" => $data['connection_group']
                        ]
                    );
                }

                $backUpCache = \Config\Services::cache(new \App\Libraries\BackupCache(), false);
                if ($backUpCache->isSupported()) {
                    $backUpCache->save(
                        $data["subdomain"],
                        [
                            "database" => $data['subdomain'] . '_' . $data['client_hash'],
                            "group" => $data['connection_group']
                        ]
                    );
                }
            } catch (\Exception $ex) {
                echo json_encode(["error" => $ex->getMessage()]);
            }
        }

        return json_encode(["success" => "New Client Is Created Along With Tenant Database."]);
    }

    public function index()
    {
        $data = $this->request->getPost();

        $valid_password = false;

        $validated = $this->validate([
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
            ]
        ]);

        if ($validated) {
            $valid_password = password_verify($data['password'], getEnv('clients.show'));
            if (!$valid_password) {
                $this->validator->setError('password', 'Wrong Password');
            }
        }

        if ($validated && $valid_password) {

            $clientModel = new ClientModel();
            $items = 1000;
            if ($this->request->getGet('items')) {
                $items = $this->request->getGet('items');
            }
            $data = [
                'clients' => $clientModel->asObject()->paginate($items),
                'pager' => $clientModel->pager->getDetails(),
            ];
            $this->response->setContentType('application/json')->setJSON($data)->send();
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function delete(String $id)
    {
        $data = $this->request->getPost();
        $valid_password = false;

        $validated = $this->validate([
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
            ]
        ]);

        if ($validated) {
            $valid_password = password_verify($data['password'], getEnv('clients.delete'));
            if (!$valid_password) {
                $this->validator->setError('password', 'Wrong Password');
            }
        }

        if ($validated && $valid_password) {
            (new ClientRepository())->delete($id);
            $this->response->setContentType('application/json')->setJSON(['success' => 'Client has been deleted.'])->send();
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function checkIfClientIsInDeletionQueue()
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));

        $complete_domain = $_SERVER['HTTP_HOST'];

        $subdomain = explode('.', $complete_domain)[0];

        $deleted_clients = (new ClientRepository())->onlyDeleted()->filterData('subdomain', $subdomain)->index(null, null, null, null, 25, '{"' . 'created_at' . '" : "ASC"}', false);

        if (count($deleted_clients['data_list']) > 0) {
            $this->response->setContentType('application/json')->setJSON(['deleted' => true])->send();
        } else {
            $this->response->setContentType('application/json')->setJSON(['deleted' => false])->send();
        }
    }

    /**
     * updateCacheForClients Updates the caches as well as backup cache details
     * print_r(md5(db_connect()->database));
     * After running this method. Need to make folder for the images and upload for the client 
     * with new database database configuration. This may not be needed for future clients.
     *
     * @return void
     */
    public function updateCacheForClients(): void
    {
        $cache = \Config\Services::cache();
        $backUpCache = \Config\Services::cache(new \App\Libraries\BackupCache(), false);

        $clientModel = new ClientModel();
        $clients = $clientModel->asArray()->findAll();
        foreach ($clients as $client) {
            if ($cache->isSupported()) {
                //delete old cache
                $cache->delete($client['subdomain']);

                //update new cache
                $cache->save(
                    $client['subdomain'],
                    [
                        "database" => $client['subdomain'] . '_' . $client['client_hash'],
                        "group" => $client['connection_group']
                    ]
                );
            }

            if ($backUpCache->isSupported()) {
                //delete old cache
                $backUpCache->delete($client['subdomain']);

                //update new cache
                $backUpCache->save(
                    $client['subdomain'],
                    [
                        "database" => $client['subdomain'] . '_' . $client['client_hash'],
                        "group" => $client['connection_group']
                    ]
                );
            }
        }

        $this->response->setContentType('application/json')->setJSON(['success' => 'Cache has been updated.'])->send();
    }
}
