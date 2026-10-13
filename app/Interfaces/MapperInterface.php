<?php

namespace App\Interfaces;

/**
 * MapperInterface This class maps the fields in the template error.
 */
interface MapperInterface
{
    /**
     * exactMatch
     *
     * @param  string $array_variable
     * @param  mixed $input
     * @param  mixed $default
     * @return String
     */
    public function exactMatch(string $array_variable, $input, $default = ""): String;
}
