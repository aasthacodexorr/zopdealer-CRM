<?php namespace App\Libraries\Syndication;

use App\Interfaces\SyndicationInterface;

class SyndicationFactory {

  private static $syndication = "App\Libraries\Syndication\Outbound\\";

  public static function getSyndicationInstance($config): ?SyndicationInterface {

    $parameter = $config->source;
    try {
      $myClass = self::$syndication . ucfirst($parameter);
      if (class_exists($myClass)) {
        return new $myClass($config);
      }
    } catch (Exception $e) {
      //echo $e->getMessage();
    }

    return null;
  }

}
