<?php namespace Tenant\App\Entities;

use CodeIgniter\Entity;
use Carbon\Carbon;

class DealFinance extends Entity
{
    public function setFirstPaymentDate(string $dateString)
    {   if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null'){
        $this->attributes['first_payment_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }
}