<?php

namespace Tenant\App\Entities;

use Carbon\Carbon;

use CodeIgniter\Entity;

class Review extends Entity
{

    public function setReviewDateTime(string $dateString)
    {

        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {

            $this->attributes['review_date_time'] = Carbon::parse($dateString, 'UTC');
        } else {
            $this->attributes['review_date_time'] = null;
        }

        return $this;
    }

    public function setReviewReplyDateTime(string $dateString)
    {

        if (!empty(trim($dateString)) && !is_null($dateString) && $dateString != 'null') {

            $date = Carbon::parse($dateString, 'UTC');
            $this->attributes['review_reply_date_time'] = Carbon::parse($dateString, 'UTC');
        } else {
            $this->attributes['review_reply_date_time'] = null;
        }

        return $this;
    }
}
