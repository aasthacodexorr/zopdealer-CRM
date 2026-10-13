<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity;

class WorkOrder extends Entity
{

    public function setCheckInDateTime(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['check_in_date_time'] = Carbon::parse($dateString, 'UTC');
        } else {
            $this->attributes['check_in_date_time'] = NULL;
        }
        return $this;
    }

    public function setCheckOutDateTime(string $dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['check_out_date_time'] = Carbon::parse($dateString, 'UTC');
        } else {
            $this->attributes['check_out_date_time'] = NULL;
        }
        return $this;
    }
}
