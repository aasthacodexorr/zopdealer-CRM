<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class SelectFormElement extends FormElementBuilder
{
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
        $value = isset($this->value) && $this->value != '' ? 'value= "' . $this->value . '"' : '';
        $id = isset($this->id) ? $this->id : '';
        $class = isset($this->class) ? $this->class : 'col-md-3 mb-3';
        $type = isset($this->type) ? $this->type : '';
        $validation = isset($this->validation) ? $this->validation : '';

        $required_validation = strpos($validation, 'required');
        $required_indicator = gettype($required_validation) != "boolean" ? '<sub class="fixed-color-set">*</sub>' : '';
        $required_attribute = gettype($required_validation) != "boolean" ? 'required' : '';
        $options = json_decode($this->opitons, TRUE);

        $template_string = '<div class="' . $class . '"> ';
        $template_string .= ' <label for="'.  $id .'">'. $label . ' ' . $required_indicator .'</label>';
        $template_string .= '<select ' . $required_attribute  . ' type="' . $type . '" id="' . $id . '" class="custom-select" placeholder="' . $placeholder . '" name="' . $name . '" >';

        $template_string .= '<option disabled selected value=""> Select An Option </option>';
        foreach ($options as $option) {
            if ($option['default'] == "1") {
                $template_string .= '<option selected value="' . $option['value'] . '">' . $option['label'] . '</option>';
            } else {
                $template_string .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
            }
        }


        $template_string .=   '</select>';
        $template_string .= '<div class="invalid-feedback">';
        $template_string .= 'Error Message';
        $template_string .= ' </div>';
        $template_string .= ' </div>';

        return $template_string;
    }
}
