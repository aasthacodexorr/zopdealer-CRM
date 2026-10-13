<?php

namespace App\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\GlobalSyndicationCompanyRepository;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\InventoryRepository;

/**
 * GlobalSyndicationCompanyController This Controller validates the incoming data from the API
 * for creating new syndication, assigning or removing syndication company from the client as well
 * as changing the default status of the syndication companies assigned to the client.
 */
class GlobalSyndicationCompanyController extends BaseController
{

    /**
     * repository stores an instance of Global Syndication Company
     *
     * @var GlobalSyndicationCompanyRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new GlobalSyndicationCompanyRepository();
    }

    /**
     * index Retrives list of Global Syndication Companies
     *
     * @return void
     * @api
     */
    public function index()
    {
        $paginate = true;
        $items = 25;

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        $response = $this->repository->index($paginate, $items);
        $this->response->setContentType('application/json')->setJSON($response)->send()->setHeader('Access-Control-Allow-Origin', '*');
    }

    /**
     * create Creates entry of new Syndication Company
     *
     * @return void
     */
    public function create()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png]',
                'max_size[file,32000]',
            ],
        ]);

        if ($validated) {

            $file = $this->request->getFile('file');
            $name = $file->getRandomName();

            CloudStorageFactory::getInstance()->save($file, ROOT_IMAGE . '/' . md5(db_connect()->database) . '/' . $name);
            $data['image_url'] = "/" . ROOT_IMAGE . "/" . md5(db_connect()->database) . "/" . $name;


            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
                exit;
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                exit;
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
            exit;
        }
    }

    /**
     * removeSyndicationCompanyForClient Removes Sydnication Company from the list 
     * of Client.
     *
     * @param  string $slug Pass the slug of the syndication company to be removed.
     * @return void
     */
    public function removeSyndicationCompanyForClient($slug)
    {

        if (!service('authPolicy')->isSuperOwner()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have enough permissions.'])->send();
            exit;
        }

        $configurationRepository = new ConfigurationRepository();

        $syndicationConfiguration = $configurationRepository->getActiveModuleConfiguration('syndication');

        $singleConfiguration = $syndicationConfiguration[0];
        $set_syndication_company = '';
        if (isset($singleConfiguration->key)) {
            $set_syndication_company = json_decode($singleConfiguration->value, true);
        }
        $set_syndication_company = array_filter($set_syndication_company, function ($single) use ($slug) {
            return $single["slug"] != $slug;
        });

        $data = [];
        $data[$singleConfiguration->key] = json_encode(array_values($set_syndication_company));
        $response = $configurationRepository->setConfiguration($data);

        $this->alterCurrentDefaultSyndication($set_syndication_company);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * updateSyndicationCompanyForClient This method adds a new syndication company to the
     * list of syndication companies in the client database.
     *
     * @return void
     */
    public function updateSyndicationCompanyForClient()
    {
        if (!service('authPolicy')->isSuperOwner()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have enough permissions.'])->send();
            exit;
        }

        $data = $this->request->getPost();

        $validated = $this->validate([
            'label' => ['label' => 'Label', 'rules' => 'required'],
            'slug' => ['label' => 'Slug', 'rules' => 'required'],
        ]);

        if ($validated) {
            $configurationRepository = new ConfigurationRepository();

            $syndicationConfiguration = $configurationRepository->getActiveModuleConfiguration('syndication');

            $singleConfiguration = $syndicationConfiguration[0];
            $set_syndication_company = '';
            if (isset($singleConfiguration->key)) {
                $set_syndication_company = json_decode($singleConfiguration->value, true);
            }

            foreach ($set_syndication_company as $key => $value) {
                if ($value['slug'] == $data['slug']) {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Syndication company already added for the client.'])->send();
                    exit;
                }
            }

            array_push($set_syndication_company, [
                "label" => $data["label"],
                "slug" => $data["slug"],
                "status"   => "1"
            ]);

            $formated_data = [];
            $formated_data[$singleConfiguration->key] = json_encode($set_syndication_company);
            $response = $configurationRepository->setConfiguration($formated_data);

            $this->alterCurrentDefaultSyndication($set_syndication_company);

            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    /**
     * updateStatusOfSyndicationCompanyForClient This method is to update the default flag of the Syndication Company
     * in the client database.
     *
     * @param  string $slug This method accepts the slug of the company whose status flag has to be changed.
     * @return void
     */
    public function updateStatusOfSyndicationCompanyForClient($slug)
    {
        $data = $this->request->getPost();
        $validated = $this->validate([
            'status' => ['status' => 'Status', 'rules' => 'required'],
        ]);

        if ($validated) {
            $configurationRepository = new ConfigurationRepository();

            $syndicationConfiguration = $configurationRepository->getActiveModuleConfiguration('syndication');

            $singleConfiguration = $syndicationConfiguration[0];

            $set_syndication_company = '';
            if (isset($singleConfiguration->key)) {
                $set_syndication_company = json_decode($singleConfiguration->value, true);
            }

            if (count($set_syndication_company) > 0) {
                foreach ($set_syndication_company as $key => $value) {
                    if ($value['slug'] == $slug) {
                        $set_syndication_company[$key]["status"] = $data["status"];
                    }
                }

                $formated_data = [];
                $formated_data[$singleConfiguration->key] = json_encode(array_values($set_syndication_company));
                $response = $configurationRepository->setConfiguration($formated_data);

                $this->alterCurrentDefaultSyndication($set_syndication_company);

                if (isset($response['success'])) {
                    $this->response->setContentType('application/json')->setJSON($response)->send();
                } else {
                    $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
                }
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Syndication company not found.'])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    /**
     * removeSyndicationCompanyForClientInventory This function removes the slug from the inventory of the client
     *
     * @return void
     */
    public function removeSyndicationCompanyFromClientInventory($slug)
    {
        if (!service('authPolicy')->isSuperOwner()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have enough permissions.'])->send();
            exit;
        }

        $inventoryRepository = new InventoryRepository();
        $response = $inventoryRepository->removeSyndicationSlugFromAllInventories($slug);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    /**
     * addSyndicationCompanyForClientInventory This function adds the slug to the inventory of the client provided it is enabled.
     *
     * @param  mixed $slug
     * @return void
     */
    public function addSyndicationCompanyForClientInventory($slug)
    {
        if (!service('authPolicy')->isSuperOwner()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'You do not have enough permissions.'])->send();
            exit;
        }

        $configurationRepository = new ConfigurationRepository();

        $syndicationConfiguration = $configurationRepository->getActiveModuleConfiguration('syndication');

        $singleConfiguration = $syndicationConfiguration[0];
        $set_syndication_company = '';
        if (isset($singleConfiguration->key)) {
            $set_syndication_company = json_decode($singleConfiguration->value, true);
        }

        $syndication_company_found  = false;
        foreach ($set_syndication_company as $key => $value) {
            if ($value['slug'] == $slug) {
                $syndication_company_found = true;
            }
        }

        if (!$syndication_company_found) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => 'Syndication company not enabled for the client.'])->send();
            exit;
        }

        $inventoryRepository = new InventoryRepository();
        $response = $inventoryRepository->addSyndicationSlugForAllInventories($slug);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }


    /**
     * options This method is for the CORS redirection.
     *
     * @param  mixed $any This paramter captures the route for the CORS to perform redirection.
     * @return void
     */
    public function options($any)
    {
        return $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200); //status code
    }

    private function alterCurrentDefaultSyndication(array $set_syndication_company): void
    {
        try {
            $set_syndication_company_slug = [];

            $set_syndication_company_slug = array_filter($set_syndication_company, function ($current_syndication_active_company) {
                return (int)($current_syndication_active_company["status"]) == 1;
            });

            $set_syndication_company_slug = array_map(function ($current_syndication_active_company) {
                return $current_syndication_active_company["slug"];
            }, $set_syndication_company_slug);

            $set_syndication_company_slug = array_values($set_syndication_company_slug);

            $set_syndication_company_slug = json_encode($set_syndication_company_slug);

            $db      = \Config\Database::connect();
            $db->query("ALTER TABLE `inventory` MODIFY `syndication` VARCHAR(255) DEFAULT '" .  $db->escapeString($set_syndication_company_slug) . "'");
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }
}
