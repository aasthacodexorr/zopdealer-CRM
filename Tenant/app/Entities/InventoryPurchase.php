<?php

namespace Tenant\App\Entities;

use CodeIgniter\Entity;
use Carbon\Carbon;

class InventoryPurchase extends Entity
{
    public function setPurchaseDate(string $dateString)
    {
        $this->attributes['purchase_date'] = null;
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['purchase_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
        }
        return $this;
    }

    public function setPaymentDate($dateString)
    {
        $this->attributes['payment_date'] = null;
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['payment_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }

    public function setTitleReceivedDate($dateString)
    {
        $this->attributes['title_received_date'] = null;
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['title_received_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }

    public function setTitleTransferDate($dateString)
    {
        $this->attributes['title_transfer_date'] = null;
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['title_transfer_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }

    public function setLienPaidDate($dateString)
    {
        $this->attributes['lien_paid_date'] = null;
        if (!is_null($dateString)) {
            if (gettype($dateString) === 'string') {
                if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
                    $this->attributes['lien_paid_date'] = Carbon::createFromIsoFormat('MM/DD/YYYY', $dateString, 'UTC');
                }
            }
        }
        return $this;
    }
}
