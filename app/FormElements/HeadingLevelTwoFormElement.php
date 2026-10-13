<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class HeadingLevelTwoFormElement extends FormElementBuilder
{
    public function render(): String
    {
        $value = isset($this->value) ? $this->value : '';
        return '<h2 class="col-md-12">'.  $value .'</h2>';

    }
}