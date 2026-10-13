<?php

namespace App\Interfaces;


/**
 * FormInterface This class gives the structure of the Form Builder such as Lead Form Template and Lead Manager.
 */
interface FormInterface {
    
    /**
     * getAllForms This deisplays all forms with pagination
     *
     * @return array
     */
    public function getAllForms($items = 10, $paginate = true) : array;
        
    /**
     * getElements Get the form and all the elements of the form.
     *
     * @param  mixed $id 
     * @return array
     */
    public function getElements($id) : array ;
    
    /**
     * saveElements Save Elements. Accepts Array paramter and returns the status
     *
     * @param  mixed $data
     * @return array
     */
    public function saveElements(array $data, $id = null) : array;

    public function validateElements() : array;
    
    /**
     * renderElements This renders the elements of the form.
     *
     * @return void
     */
    public function renderElements() : String;
}