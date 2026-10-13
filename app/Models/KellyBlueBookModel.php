<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * KellyBlueBookModel This model stores information of inventory from Kelly Blue Book.
 */
class KellyBlueBookModel extends Model
{
    protected $table      = 'kelly_blue_book';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'year', 'make', 'model', 'trim', 'page_url', 'action'
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
}
