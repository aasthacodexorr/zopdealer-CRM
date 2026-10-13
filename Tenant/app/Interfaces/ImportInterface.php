<?php

namespace Tenant\app\Interfaces;

/***
 * ImportInterface defines the structure of the classes for importing data in the system.
 */
interface ImportInterface
{
    
    /**
     * validate This method validates the data and forwards the errors and data
     * to the next valid class in the namespaces.
     *
     * @param  mixed $data
     * @param  mixed $namespaces
     * @param  mixed $errors
     * @return array
     */
    function validate(array $data, array $namespaces, array $errors) : array;
    
    /**
     * process This method processes the data and forwards the response and the data
     * to the next valid class in the namespaces.
     *
     * @param  mixed $data
     * @param  mixed $namespaces
     * @param  mixed $response
     * @return array
     */
    function process(array $data, array $namespaces, array $response) : array;
}
