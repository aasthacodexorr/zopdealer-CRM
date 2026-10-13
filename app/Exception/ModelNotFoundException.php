<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b>' . $this->getMessage() . '</b>. Model Not Found.';
        return $errorMsg;
    }
}
