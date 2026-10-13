<?php namespace Tenant\App\Entities;

use CodeIgniter\Entity;
use Carbon\Carbon;

class Payment extends Entity
{
    public function setDate($dateString)
    {   
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }

}