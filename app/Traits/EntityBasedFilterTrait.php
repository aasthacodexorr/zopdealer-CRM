<?php

namespace App\Traits;

use App\Repositories\AdvanceRepository;

trait EntityBasedFilterTrait
{
    protected $attributes = [];
    protected $values = [];

    /**
     * setEntityAttributeAndFilterValue
     *
     * @param  String $attribute
     * @param  mixed $value
     * @return 
     */
    public function setEntityAttributeAndFilterValue(String $attribute, $value)
    {
        array_push($this->attributes, $attribute);
        array_push($this->values, $value);

        return $this;
    }

    /**
     * applyEntityFilters
     *
     * @param  AdvanceRepository $repository
     * @return
     */
    public function applyEntityFilters(AdvanceRepository $repository)
    {
        foreach ($this->attributes as $key => $attribute) {
            $repository->filterData($attribute, $this->values[$key]);
        }

        return $this;
    }
}
