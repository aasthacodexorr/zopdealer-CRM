<?php namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\SyndicationModel;
use Tenant\app\Repositories\InventoryRepository;

class SyndicationRepository extends BaseRepository {

  protected $_items;

  public function __construct() {
    parent::__construct(new SyndicationModel());
  }

  public function inventoryListBySyndication($key) {
    $inventory = new InventoryRepository();
    return $inventory->onlyVisible()->onlyInstock()->bySyndicationFilter($key)->findAll();
  }

  public function inventoryListWithoutSyndication() {
    $inventory = new InventoryRepository();
    return $inventory->onlyVisible()->onlyInstock()->findAll();
  }

  public function inventoryListBySyndicationYesterday($key) {
    $inventory = new InventoryRepository();
    return $inventory->onlyVisible()->onlyInstock()->getYesterdayInventory()->bySyndicationFilter($key)->findAll();
  }
  
  public function inventoryListBySyndicationEvenOdd($key,$token) {
    $inventory = new InventoryRepository();
    return $inventory->onlyVisible()->onlyInstock()->onlyByDay($token)->bySyndicationFilter($key)->findAll();    
  }
  
  public function inventoryGetFile($header,$iid) {
      $url = "";
      $inventory = new InventoryRepository();
      $row = $inventory->getFile('Vehicle History Report', $iid);

      if (count($row) > 0) {
        $file_path = $row[0]['file_url'];
        if (gettype($file_path) == "string") {
            $url = $file_path;
        }
      }
      return $url ;
  }    

  protected function syndicationList() {
    return ($this->_items == null) ?
      $this->model->orderBy('syndication_feed_id', 'ASC')->asArray()->findAll() :
      $this->model->orderBy('syndication_feed_id', 'ASC')->asArray()->paginate($this->_items);
  }

  public function filterByStatus($status) {
    $this->model->where('status', $status);
    return $this;
  }

  public function filterBySource($source) {
    $this->model->where('source', $source);
    return $this;
  }  
  

  // @TODO : Check the logic if syndication is active or not before serving result.( PENDING )
//  public function onlyActive() {
//    $this->model->where('status', 'active');
//    return $this;
//  }


  public function addSelect($fields = ['*']) {
    $this->model->select($fields);
    return $this;
  }

  public function getRecordToQueue($source=null) 
  {
    $feedsx = $this->addSelect(['syndication_feed_id'])->filterByStatus('active');            
    if($source!=null){       
        $feedsx->filterBySource($source);            
    }
    $feeds = $feedsx->getSyndicationList();

    if (!count($feeds) > 0) {
      return 0;
    }

    return $feeds;
  }

  public function getSocialRecordToQueue() {
    $feeds = $this->addSelect(['syndication_feed_id'])
            ->filterByStatus('offline')
            ->getSyndicationList();

    if (!count($feeds) > 0) {
      return 0;
    }

    return $feeds;
  } 
  public function getSyndicationList($items = null) {
    $this->_items = $items;
    return $this->syndicationList();
  }

  public function getInventoryDescParse($single_inventory) {
     // Ensure database connection uses UTF-8
     $db = \Config\Database::connect();
     $db->query("SET NAMES 'utf8mb4'");

     $parseDesc =  (new InventoryRepository())->replacePlaceholders($single_inventory);
     return $parseDesc['vehicle_description'] ;
  }
  
  public function getInventoryTitle($single_inventory) {
    $parseDesc =  (new InventoryRepository())->replacePlaceholders($single_inventory);
    return $parseDesc['title'] ;
 }

}
