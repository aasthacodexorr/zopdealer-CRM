<?php 
namespace App\Libraries;

use Config\Cache;

/**
 * BackupCache This is a backup Cache configuration which presist the data in file format so it can be easily switched
 * with Redis Handler in case of ever needed.
 */
class BackupCache extends Cache 
{
    public $handler = 'file';
    public $backupHandler = 'predis';
}