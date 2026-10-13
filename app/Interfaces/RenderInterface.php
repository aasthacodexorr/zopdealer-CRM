<?php

namespace App\Interfaces;

/**
 * RenderInterface this interface should be inherited by the Components of Form Elements while rendering.
 */
interface RenderInterface{

    public function render() : String;
}