<?php

namespace Tenant\App\Entities;

use CodeIgniter\Entity;
use Override;

class MarketValuation extends Entity
{
    #[Override]
    public function __construct(?array $data = null)
    {
        $this->attributes['year'] = null;
        return parent::__construct($data);
    }
    public function setYear($year)
    {
        if (is_numeric($year)) {
            $this->attributes['year'] = $year;
        } else {
            $this->attributes['year'] = null;
        }
        return $this;
    }
}
