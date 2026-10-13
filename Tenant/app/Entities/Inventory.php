<?php namespace Tenant\App\Entities;
use Carbon\Carbon;

use CodeIgniter\Entity;

class Inventory extends Entity
{

    public function setStockDate(string $dateString)
    {   if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null'){
        $this->attributes['stock_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

    public function setSpecialPriceDate($dateString)
    {
        if(!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null' ){
        $this->attributes['special_price_date'] = Carbon::parse($dateString, 'UTC');
        }
        return $this;
    }
}
