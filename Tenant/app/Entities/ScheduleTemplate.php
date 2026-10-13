<?php namespace Tenant\App\Entities;
use Carbon\Carbon;

use CodeIgniter\Entity;

class ScheduleTemplate extends Entity
{

    public function setDateTime(string $dateString)
    {   if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null'){
        $this->attributes['datetime'] = Carbon::createFromIsoFormat('MM/DD/YYYY HH:mm', $dateString, 'UTC');
        }
        return $this;
    }
}
