<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class HeadingLevelFourFormElement extends FormElementBuilder
{
    public function render(): String
    {
        $value = isset($this->value) ? $this->value : '';
        return '<h4 class="col-md-12">'.  $value .'</h4>';

    }
}