<?php

namespace App\FormElements;

use App\Builder\FormElementBuilder;

class FileFormElement extends FormElementBuilder
{
    public function render(): String
    {
        $this->type = 'file';
        return parent::render();
    }
}
