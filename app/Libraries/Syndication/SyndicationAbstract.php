<?php namespace App\Libraries\Syndication;

use App\Interfaces\SyndicationInterface;

abstract class SyndicationAbstract implements SyndicationInterface {

  protected $config;
  protected $file;
  protected $host;
  protected $user;
  protected $password;
  protected $dealerid;
  protected $dbase;
  protected $header;
  protected $imgPath;

}
