<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class TextAreaFormElement extends FormElementBuilder
{
    public function render(): String
    {
        $placeholder = isset($this->placeholder) ? $this->placeholder : '';
        $label = isset($this->label) ? $this->label : '';
        $name = isset($this->name) ? $this->name : '';
        $value = isset($this->value) && $this->value != '' ? $this->value : '';
        $id = isset($this->id) ? $this->id : '';
        $class = isset($this->class) ? $this->class : 'col-md-3 mb-3';
        $type = isset($this->type) ? $this->type : '';
        $validation = isset($this->validation) ? $this->validation : '';
        $required_validation = strpos($validation, 'required');
        $required_indicator = gettype($required_validation) != "boolean" ? '<sub class="fixed-color-set">*</sub>' : '';
        $required_attribute = gettype($required_validation) != "boolean" ? 'required' : '';

        $template_string = '<div class="' . $class . '"> ';
        $template_string .= ' <label for="'.  $id .'">'. $label . ' ' . $required_indicator .'</label>';
        $template_string .= '<textarea' . $required_attribute . '  id="' . $id . '" class="form-control" placeholder="' . $placeholder . '" name="' . $name . '" >' . $value . '</textarea>';
        $template_string .= '<div class="invalid-feedback">';
        $template_string .= 'Error Message';
        $template_string .= ' </div>';
        $template_string .= ' </div>';

        return $template_string;
    }
}
