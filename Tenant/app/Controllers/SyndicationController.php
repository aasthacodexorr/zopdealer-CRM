<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\SyndicationRepository;
use App\Policies\AuthPolicy;

class SyndicationController extends \App\Controllers\BaseController
{

  public $data = [];

  public function index()
  {

    //service('authPolicy')->check('Syndication-read');
    if (service('authPolicy')->hasElevatedRoles()) {
      $syndicationRepository = new SyndicationRepository();
      $this->data['data']['syndication'] = $syndicationRepository->getActiveSyndication();
      $this->data['data']['logs'] = $syndicationRepository->getSyndicationLog();
      $this->data['data']['count'] = $syndicationRepository->getAllSyndicationCount();

      foreach($this->data['data']['logs'] as $log_index => $log_value){
        $folder_name = db_connect()->database;
        $folder_name = preg_replace('/(\w*)_/','',$folder_name);
        
        if(service('authPolicy')->isSuperOwner()){
          $this->data['data']['logs'][$log_index]->file_name = '/media/export/' . $folder_name .'/' . $this->data['data']['logs'][$log_index]->file_name;
        }
      }
      $this->response->setContentType('application/json')->setJSON($this->data)->send();
    } else {
      $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
    }
  }

  public function healthcheck()
  {

    service('authPolicy')->check('Syndication-read');
    $syndicationRepository = new SyndicationRepository();

    if ($this->request->getGet('c')) {
      $synd = (string) $this->request->getGet('c');
      $activeSynd = $syndicationRepository->getActiveSyndication();
      $this->data['data']['health'] = (in_array($synd, array_keys($activeSynd))) ? $syndicationRepository->getHealthCheck($synd) : [];
    }

    $this->data['status'] = "OK";
    $this->data['msg'] = "";
    // if(count($this->data['data']['health'] < 1)){
    //    $this->data['msg'] = "NO RECORD FOUND";
    //   }

    $this->response->setContentType('application/json')->setJSON($this->data)->send();
  }
}
