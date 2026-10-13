<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class ParagraphFormElement extends FormElementBuilder
{
    public function render(): String
    {
        $value = isset($this->value) ? $this->value : '';
        return '<p class="col-md-12">'.  $value .'</hp>';

    }
}