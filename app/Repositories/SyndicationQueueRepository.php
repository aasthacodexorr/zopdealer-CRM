<?php namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\SyndicationQueueModel;
use Tenant\app\Repositories\InventoryRepository;

class SyndicationQueueRepository extends BaseRepository {

  public $_builder = null;

  public function __construct() {
    parent::__construct(new SyndicationQueueModel());
    $this->_builder = $this->model->builder();
  }

  public function addtoQueue($feeds) {
    return $this->model->insertBatch($feeds);
  }

  public function getQueueList() {
    $this->_builder->select("syndication_feed.*, syndication_queue.*");
  //  $this->_builder->select("syndication_feed.syndication_feed_id ,syndication_feed.domain_name,syndication_feed.source, syndication_queue.*");
    $this->_builder->join('syndication_feed', 'syndication_feed.syndication_feed_id = syndication_queue.syndication_feed_id', 'left');
    $this->_builder->where('syndication_queue.status', 'pending');
    $where = "(syndication_feed.status='active' OR syndication_feed.status='offline')";
    $this->_builder->where($where);
    $this->_builder->limit(100);
    // echo $sql = $this->_builder->getCompiledSelect(); exit;

    return $this;
  }

  public function getResult() {

    return $this->_builder->get()->getResult();
  }

  public function getRow() {

    return $this->_builder->get()->getRow();
  }

  public function clearQueueList() {

   return $this->_builder->truncate();
    //return "Queue has been cleared";
  }
  
  public function skipQueueRecordById($id) {

    $this->_builder->set('status','skipped');
    $this->_builder->where('syndication_queue_id', $id);
    return $this->_builder->update();
   }

  public function updateLogList($id, $logs) {

    $this->dbase = \Config\Database::connect();

    // Make use of ENV file, wil crash withou that
    $this->dbase->setDatabase(getEnv('database.default.database'));

    foreach ($logs as $key => $log) {
      $this->_builder->set($key, $log);
    }

    $this->_builder->where('syndication_queue_id', $id);
    $this->_builder->update();
  }

  public function getSingleRecord() {

    $this->_builder->limit(1);
    return $this;
  }

  public function getSingleRecordById($id) {
    $this->_builder->where('syndication_queue.syndication_queue_id', $id);
    $this->_builder->limit(1);
    return $this;
  }
  public function getRecordToProcess() {

    $inventory = new InventoryRepository();
    return $inventory->onlyVisible()->findAll();
  }

}
