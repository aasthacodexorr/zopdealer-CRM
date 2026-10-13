<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity;

class VoucherTemplate extends Entity
{
    public function setExpiryDate($dateString)
    {
        $this->attributes['expiry_date'] = null;
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['expiry_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }
}
