<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class SectionFormElement extends FormElementBuilder
{
    /**
     * render Default Render Can be Overriden by subclasses
     *
     * @return String
     */
    public function render(): String {
        return '</div><div class="form-row">';
    }
}