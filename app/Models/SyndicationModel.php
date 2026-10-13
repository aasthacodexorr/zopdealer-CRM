<?php

namespace App\Models;

use CodeIgniter\Model;

class SyndicationModel extends Model {

  protected $table = 'syndication_feed';
  protected $primaryKey = 'syndication_feed_id';
  protected $allowedFields = [
      'client_id', 'domain_name', 'source', 'ftp_host',
      'ftp_user', 'ftp_pass', 'ftp_port', 'file_name',
      'identity_id', 'created_at', 'modified_by', 'cron',
      'comment', 'status', 'folder', 'db_name'
  ];
  protected $useSoftDeletes = true;
  protected $returnType = 'App\Entities\Syndication';
  protected $useTimestamps = true;
  protected $validationRules = [
      'client_id' => [
          "label" => 'Client ID',
          "rules" => 'required|min_length[3]'
      ],
      'domain_name' => [
          "label" => 'Domain Name',
          "rules" => 'required'
      ],
      'source' => [
          "label" => 'Syndication Source',
          "rules" => 'required|min_length[2]'
      ],
      'ftp_host' => [
          "label" => 'FTP Host',
          "rules" => 'required|min_length[6]'
      ],
      'ftp_user' => [
          "label" => 'FTP User',
          "rules" => 'required|min_length[2]'
      ],
      'ftp_pass' => [
          "label" => 'FTP Password',
          "rules" => 'required|min_length[2]'
      ],
      'file_name' => [
          "label" => 'Port',
          "rules" => 'required|min_length[2]'
      ],
      'created_at' => [
          "label" => 'Created At',
          "rules" => 'required'
      ],
      'modified_by' => [
          "label" => 'Last Modified By',
          "rules" => 'required'
      ],
      'status' => [
          "label" => 'Status',
          "rules" => 'required'
      ],
  ];

}
