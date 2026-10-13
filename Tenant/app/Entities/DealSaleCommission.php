<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;

use CodeIgniter\Entity;

class DealSaleCommission extends Entity
{

    public function setPaidDate(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['paid_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }
}
