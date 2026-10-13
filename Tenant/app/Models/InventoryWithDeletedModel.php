<?php

namespace Tenant\app\Models;

use App\Models\SearchableModel;
use CodeIgniter\Model;

class InventoryWithDeletedModel extends InventoryModel
{
    protected $useSoftDeletes = false;
}