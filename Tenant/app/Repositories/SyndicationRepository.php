<?php namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Models\SyndicationModel;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Entities\Syndication;
use Tenant\app\Repositories\ConfigurationRepository;

class SyndicationRepository extends BaseRepository {

  private static $_module = 'syndication'; // configuration key
  protected $_syndicationName = null;
  public $limit = 20;

  public function __construct() {
    parent::__construct(new SyndicationModel());
  }

  protected function _getActiveSyndication(): array {

    $configuration = new ConfigurationRepository(); 
    $syndicationData = $configuration->findConfigurationColumnsByKey(SyndicationRepository::$_module, ['value']);

    $labels = [];
    foreach (json_decode($syndicationData->value) as $label) {
      $labels[$label->slug] = $label->label;
    }
    return $labels;
  }

  protected function _getSyndicationLog(): array {
    
    $builder = $this->model->builder();
    $builder->select('*');
    $builder->limit($this->limit);
    $builder->orderBy('updated_at', "DESC");
    return $builder->get()->getResult();
  }

  protected function _getAllSyndicationCount(): array {

    $activeSynd = $this->_getActiveSyndication();
    $labels = [];

    foreach (array_keys($activeSynd) as $key) {
      $labels[$key] = self::getSyndicationCountByName($key);
    }

    return $labels;
  }

  protected function _getSyndicationCountByName(): int {

    $db = \Config\Database::connect();
    $builder = $db->table('inventory'); // @todo : should be an inventory model
    $builder->select('syndication');
    $builder->where('visibility', '1');
    $builder->where('status', 'Instock');  
    $builder->where('deleted_at=', NULL);
    $builder->like('syndication', $this->_syndicationName);
    // $sql = $builder->getCompiledSelect();
    return $builder->countAllResults();
  }

  protected function _getHealthCheck(): array {
    
    $inventory = new InventoryModel();
    $builder = $inventory->builder();
    $builder->like('syndication', $this->_syndicationName);
    $activeSynd = $builder->get()->getResult();
    
  //  print_r($activeSynd); exit;
// $sql = $builder->getCompiledSelect();
   // $labels = [];
  //  foreach (array_keys($activeSynd) as $key) {
  //    $labels[$key] = self::getSyndicationCountByName($key);
  //  }

//    return $labels;
    
    // @TODO: Insert Validator to filter out Odd records.
    return $activeSynd;
  }

  public function updateLogList($log) {

    $syndication = new Syndication($log);
    if ($this->model->save($syndication) === false) {
      //@TODO : add error handling       
    }

    // @TODO : Updated/Curetnt need to send from Php function for Timezone change unless time is in UTC

    return true;
  }

  /*
   * Return all active syndication inventory with count
   * return array
   */

  public function getAllSyndicationCount() {
    return self::_getAllSyndicationCount();
  }

  /*
   * Return all active syndication 
   * return array
   */

  public function getActiveSyndication() {
    return self::_getActiveSyndication();
  }

  /*
   * Return all syndication logs
   * return array
   */

  public function getSyndicationLog() {
    return self::_getSyndicationLog();
  }

  /*
   * Return syndication inventory count by name
   * return int
   */

  public function getSyndicationCountByName($name) {
    $this->_syndicationName = $name;
    return self::_getSyndicationCountByName();
  }

  /*
   * Return syndication Healthcheck
   * return int
   */  
  public function getHealthCheck($name) {
    $this->_syndicationName = $name;
    return self::_getHealthCheck();
  }

}
