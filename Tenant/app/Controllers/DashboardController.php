<?php

namespace Tenant\app\Controllers;

use Tenant\app\Entities\Image;
use Tenant\app\Models\ImageModel;
use App\Policies\AuthPolicy;
use Tenant\app\Repositories\DashboardRepository;

/**
 * DashboardController : Controller for managing statistics on the dashboard.
 */
class DashboardController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DashboardRepository for aggregating the data.
     *
     * @var DashboardRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new DashboardRepository();
    }

    public function index()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $timeperiod = "all";

            if ($this->request->getGet('timeperiod')) {
                $timeperiod = $this->request->getGet('timeperiod');
            }

            $response = $this->repository->summary($timeperiod);
            if (empty($response['errors'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
