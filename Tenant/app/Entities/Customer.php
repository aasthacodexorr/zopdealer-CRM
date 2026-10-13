<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;

use CodeIgniter\Entity;

class Customer extends Entity
{


    public function setDriverLicenceExpiryDate($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['driver_licence_expiry_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
            if (!empty($this->attributes['driver_licence_expiry_date'])) {
                $this->attributes['driver_licence_expiry_date'] = $this->attributes['driver_licence_expiry_date']->format('Y-m-d');
            }
        }
        return $this;
    }

    public function setDateOfBirth($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['date_of_birth'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
            if (!empty($this->attributes['date_of_birth'])) {
                $this->attributes['date_of_birth'] = $this->attributes['date_of_birth']->format('Y-m-d');
            }
        }
        return $this;
    }
}
