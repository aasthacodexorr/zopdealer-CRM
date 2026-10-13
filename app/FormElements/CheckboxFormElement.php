<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;
use App\Helpers\Utility;

class CheckboxFormElement extends FormElementBuilder
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

                $i = 0;
                $random_unique_string = Utility::randomString(6);
                foreach ($options as $option) {
                    if ($option['default'] == "1") {
                        $template_string .=   '<div class="form-check">
                        <input checked name="' . $name . '[]" type="checkbox" value="'. $option['value'] .'" class="form-check-input" id="'.$random_unique_string . $i .'" >
                        <label class="form-check-label" for="'.$random_unique_string . $i .'">'.  $option['label'] .'</label>
                        </div>';
                    } else {
                        $template_string .=   '<div class="form-check">
                        <input name="' . $name . '[]" type="checkbox" value="'. $option['value'] .'" class="form-check-input" id="'.$random_unique_string . $i .'" >
                        <label class="form-check-label" for="'.$random_unique_string . $i .'">'.  $option['label'] .'</label>
                        </div>';

                    }
                    $i += 1;
                }

                $template_string .= '<div class="invalid-feedback">';
                $template_string .= 'Error Message';
                $template_string .= ' </div>';
                $template_string .= ' </div>';

        return $template_string;
    }
}
