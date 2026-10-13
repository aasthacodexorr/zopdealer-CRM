<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\KBB\KBB;
use App\Models\KellyBlueBookModel;
use App\Repositories\KellyBlueBookRepository;

/**
 * KellyBlueBookController This method contains API for processing Data from Kelly Blue Book
 */
class KellyBlueBookController extends BaseController
{
    /**
     * repository This repository corresponds to the KellyBlueBookRepository which can be used by any method here.
     *
     * @var KellyBlueBookRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new KellyBlueBookRepository();
        $this->kbb_api = new KBB();
        $this->kbb_api->setAPI_KEY(getenv('KBB.API_KEY'));
        $this->kbb_api->setAPI_URL(getenv('KBB.API_URL'));
    }

    public function index($column)
    {
        $query = [];
        $result = [];
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        $result = $this->repository->distinctColumn($column, $query);

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)->setContentType('application/json')->setJSON($result)->send();
    }

    public function getAllYears()
    {
        $result = $this->kbb_api->getAllYears();

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)->setContentType('application/json')->setJSON($result)->send();
    }

    public function getAllMakes(string $year)
    {
        $result = $this->kbb_api->getAllMakes($year);

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)->setContentType('application/json')->setJSON($result)->send();
    }

    public function getAllModels(string $year, string $makeID)
    {
        $result = $this->kbb_api->getAllModels($year,$makeID);

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)->setContentType('application/json')->setJSON($result)->send();
    }

    public function getAllTrims(string $year, string $modelID)
    {
        $result = $this->kbb_api->getAllTrims($year,$modelID);

        $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
            ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
            ->setStatusCode(200)->setContentType('application/json')->setJSON($result)->send();
    }
}
