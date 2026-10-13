<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity;

class TestDrive extends Entity
{
    protected $casts = [
        'id' => 'integer',
        'test_drives_customers_id' => 'integer',
        'inventory_id' => 'integer',
        'sales_rep_id' => 'integer',
        'odo_start' => 'integer',
        'odo_end' => 'integer',
        'number_plate' => 'string',
        'status' => 'string',
        'session_token' => 'string',
        'qr_code_url' => 'string',
        'customer_signature_url' => 'string',
        'insurance_url' => 'string',
        'notes' => 'string',
        'start_time' => 'datetime[Y-m-d H:i:s]',
        'end_time' => 'datetime[Y-m-d H:i:s]',
        'created_at' => 'datetime[Y-m-d H:i:s]',
        'updated_at' => 'datetime[Y-m-d H:i:s]',
        'deleted_at' => 'datetime[Y-m-d H:i:s]'
    ];

    /**
     * Set start_time from formatted date string
     */
    public function setStartTime($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['start_time'] = Carbon::parse($dateString);
        }
        return $this;
    }

    /**
     * Set end_time from formatted date string
     */
    public function setEndTime($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['end_time'] = Carbon::parse($dateString);
        }
        return $this;
    }

    /**
     * Get start_time as a plain 'Y-m-d H:i:s' string.
     * Named getStartTime() so CI4 Entity uses this instead of the $dates Time cast.
     */
    //public function getFormattedStartTime(): ?string
    public function getStartTime(): ?string
    {
        if (!empty($this->attributes['start_time'])) {
            return Carbon::parse($this->attributes['start_time'])->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get end_time as a plain 'Y-m-d H:i:s' string.
     * Named getEndTime() so CI4 Entity uses this instead of the $dates Time cast.
     */
    //public function getFormattedEndTime(): ?string
    public function getEndTime(): ?string
    {
        if (!empty($this->attributes['end_time'])) {
            return Carbon::parse($this->attributes['end_time'])->format('Y-m-d H:i:s');
        }
        return null;
    }


    /**
     * Set start_time from formatted date string
     */
    public function setCreatedAt($dateString)
    {
        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {
            $this->attributes['created_at'] = Carbon::parse($dateString);
        }
        return $this;
    }

    /**
     * Get created_at as a plain 'Y-m-d H:i:s' string.
     * Named getCreatedAt() so CI4 Entity uses this instead of the $dates Time cast.
     */
    public function getCreatedAt(): ?string
    {
        if (!empty($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get updated_at as a plain 'Y-m-d H:i:s' string.
     * Named getUpdatedAt() so CI4 Entity uses this instead of the $dates Time cast.
     */
    public function getUpdatedAt(): ?string
    {
        if (!empty($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get deleted_at as a plain 'Y-m-d H:i:s' string.
     * Named getDeletedAt() so CI4 Entity uses this instead of the $dates Time cast.
     */
    public function getDeletedAt(): ?string
    {
        if (!empty($this->attributes['deleted_at'])) {
            return Carbon::parse($this->attributes['deleted_at'])->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get duration in minutes
     */
    public function getDurationMinutes(): ?int
    {
        if (!empty($this->attributes['start_time']) && !empty($this->attributes['end_time'])) {
            $start = Carbon::parse($this->attributes['start_time']);
            $end = Carbon::parse($this->attributes['end_time']);
            return $start->diffInMinutes($end);
        }
        return null;
    }

    /**
     * Get distance traveled
     */
    public function getDistanceTraveled(): ?int
    {
        if (!empty($this->attributes['odo_start']) && !empty($this->attributes['odo_end'])) {
            return $this->attributes['odo_end'] - $this->attributes['odo_start'];
        }
        return null;
    }

    /**
     * Check if test drive is in progress
     */
    public function isInProgress(): bool
    {
        return $this->attributes['status'] === 'in_progress';
    }

    /**
     * Check if test drive is completed
     */
    public function isCompleted(): bool
    {
        return $this->attributes['status'] === 'completed';
    }

    /**
     * Check if test drive is pending
     */
    public function isPending(): bool
    {
        return $this->attributes['status'] === 'pending';
    }
}