<?php namespace Tenant\App\Entities;
use Carbon\Carbon;

use CodeIgniter\Entity;

class CustomerInsurance extends Entity
{

    public function setPolicyExpiryDate(string $dateString)
    {   if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null'){
        $this->attributes['policy_expiry_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

}
