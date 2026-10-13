<?php namespace Tenant\App\Entities;

use CodeIgniter\Entity;
use Carbon\Carbon;

class InventoryCost extends Entity
{
    public function setPurchaseDate(string $dateString)
    {   if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null'){
        $this->attributes['purchase_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

}