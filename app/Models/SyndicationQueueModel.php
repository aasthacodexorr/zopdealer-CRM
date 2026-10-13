<?php

namespace App\Models;

use CodeIgniter\Model;

class SyndicationQueueModel extends Model {

  protected $table = 'syndication_queue';
  protected $primaryKey = 'syndication_queue_id';
  protected $allowedFields = [
      'syndication_feed_id', 'scheduled_at', 'status', 'message', 'oldfile'
  ];
  protected $useSoftDeletes = false;
  protected $returnType = 'App\Entities\SyndicationQueue';
  protected $useTimestamps = true;
  protected $validationRules = [
      'syndication_feed_id' => [
          "label" => 'Syndication ID',
          "rules" => 'required'
      ]
  ];

}
