<?php

namespace App\Traits;

use App\Repositories\AdvanceRepository;

trait EntityBasedQueryTrait
{

    protected $dynamic_queries = [];

    public function setEntityQuery(String $column, String $join = "AND", String $comparator, String $type, $value_1, $value_2 = '')
    {
        array_push($this->dynamic_queries, [
            "header" => "",
            "column" => $column,
            "join" => $join,
            "comparator" => $comparator,
            "type" => $type,
            "value_1" => $value_1,
            "value_2" => $value_2
        ]);

        return $this;
    }
    
    /**
     * getEntityQuery
     *
     * @return mixed
     */
    public function getEntityQuery()
    {
        return json_encode($this->dynamic_queries);
    }
}
