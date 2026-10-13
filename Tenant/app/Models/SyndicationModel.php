<?php namespace Tenant\app\Models;

use CodeIgniter\Model;

class SyndicationModel extends Model {

  protected $table = 'syndication_log';
  protected $primaryKey = 'syndication_log_id';
  protected $allowedFields = ['created_at', 'message', 'feed_id','invcount' ,'source','file_name', 'updated_at',];
  protected $useSoftDeletes = true;
  protected $returnType = 'Tenant\app\Entities\Syndication';
  protected $useTimestamps = true;
  protected $validationRules = [
      'message' => [
          "label" => 'Message',
          "rules" => 'required'
      ],
      'feed_id' =>[
           "label" => 'Feed Id',
          "rules" => 'required'
      ]
  ];

}
