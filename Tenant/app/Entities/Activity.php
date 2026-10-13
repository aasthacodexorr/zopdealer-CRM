<?php namespace Tenant\App\Entities;

use App\Helpers\Utility;
use Carbon\Carbon;
use CodeIgniter\Entity;

class Activity extends Entity
{
    protected $full_date_format = "MM/DD/YYYY HH:mm";
    public function setStartDate(string $dateString)
    {
        if (Utility::validateDate($dateString, $this->full_date_format) ) {
            $this->attributes['start_date'] = Carbon::createFromIsoFormat($this->full_date_format, $dateString);
        }
        return $this;
    }

    public function setEndDate(string $dateString)
    {
        if (Utility::validateDate($dateString, $this->full_date_format)) {
            $this->attributes['end_date'] = Carbon::createFromIsoFormat($this->full_date_format, $dateString);
        }
        return $this;
    }

    public function setNotificationDate($dateString)
    {
        if (Utility::validateDate($dateString, $this->full_date_format)) {
            $this->attributes['notification_date'] = Carbon::createFromIsoFormat($this->full_date_format, $dateString);
        }
        return $this;
    }

}