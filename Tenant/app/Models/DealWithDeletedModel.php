<?php

namespace Tenant\app\Models;

class DealWithDeletedModel extends DealModel
{
    protected $useSoftDeletes = false;
}