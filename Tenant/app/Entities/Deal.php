<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity;

class Deal extends Entity
{

    public function setDate(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

    public function setCloseDate(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['close_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

    public function setCancellationDate(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['cancellation_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        } else {
            $this->attributes['cancellation_date'] = NULL;
        }
        return $this;
    }

    public function setDeliveryDateTime($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['delivery_date_time'] = Carbon::parse($dateString, 'UTC');
        }
        return $this;
    }
}
