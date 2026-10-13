<?php

namespace App\Builder;

use App\Interfaces\RenderInterface;
use App\Helpers\Utility;

/**
 * FormElementBuilder Object of this class accept the parameters to create the respective Forms.
 */
abstract class FormElementBuilder implements RenderInterface
{
    protected $label ;
    protected $placeholder ;
    protected $name ;
    protected $value ;
    protected $id ;
    protected $class ;
    protected $type ;
    protected $validation ;
    protected $opitons;

    /**
     * Set the value of label
     *
     * @return  self
     */ 
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set the value of placeholder
     *
     * @return  self
     */ 
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of class
     *
     * @return  self
     */ 
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the value of validation
     *
     * @return  self
     */ 
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Set the value of opitons
     *
     * @return  self
     */ 
    public function setOpitons($opitons)
    {
        $this->opitons = $opitons;

        return $this;
    }
    
    /**
     * reset Resets the values of the Object properties
     *
     * @return void
     */
    public function reset(){
        unset(
        $this->label,
        $this->placeholder,
        $this->name,
        $this->value,
        $this->id,
        $this->class,
        $this->type,
        $this->validation,
        $this->opitons
        );
    }
    
    /**
     * initialize
     *
     * @param  mixed $name
     * @param  mixed $value
     * @param  mixed $type
     * @return void
     */
    public function initialize($name, $value, $type)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        
    }
    
    /**
     * render Default Render Can be Overriden by subclasses
     *
     * @return String
     */
    public function render(): String
    {
        $placeholder = isset($this->placeholder) ? $this->placeholder : '';
        $label = isset($this->label) ? $this->label : '';
        $name = isset($this->name) ? $this->name : '';
        $value = isset($this->value) && $this->value != '' ? 'value= "'. $this->value .'"' : '';
        $id = isset($this->id) ? $this->id : '';
        $class = isset($this->class) ? $this->class : 'col-md-3 mb-3';
        $type = isset($this->type) ? $this->type : '';
        $validation = isset($this->validation) ? $this->validation : '';

        $required_validation = strpos($validation, 'required');
        $required_indicator = gettype($required_validation) != "boolean" ? '<sub class="fixed-color-set">*</sub>' : '';
        $required_attribute = gettype($required_validation) != "boolean" ? 'required' : '';

        $template_string = '<div class="' . $class . '"> ';
        $template_string .= ' <label for="'.  $id .'">'. $label . ' ' . $required_indicator .'</label>';
        $template_string .= '<input ' . $required_attribute  . ' type="' . $type . '" id="' . $id . '" class="form-control" placeholder="' . $placeholder . '" name="' . $name . '" ' . $value . ' >';
        $template_string .= '<div class="invalid-feedback">';
        $template_string .= 'Error Message';
        $template_string .= ' </div>';
        $template_string .= ' </div>';

        return $template_string;
    }
}
