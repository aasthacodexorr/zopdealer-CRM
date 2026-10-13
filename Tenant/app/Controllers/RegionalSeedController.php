<?php

namespace Tenant\app\Controllers;

use Tenant\app\Libraries\RegionalSeed\RegionalSeedFactory;
use Tenant\app\Repositories\ConfigurationRepository;

class RegionalSeedController extends \App\Controllers\BaseController
{
    public function seed($region)
    {
        if (service('authPolicy')->isSuperOwner()) {
            try {
                $data = $this->request->getPost();
                $regionalFactory = RegionalSeedFactory::getInstance($region);
                $data['documentMethods'] = json_decode($data["documentMethods"], true);
                $regionalFactory->executeSeed($data['documentMethods']);
                
                $this->response->setContentType('application/json')->setJSON([
                    'success' => 'Regional Seed configuration successful.'
                ])->send();
            } catch (\Exception $ex) {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }
}
