<?php

namespace App\Libraries\LeadProcessing\Exceptions;

use Exception;

/**
 * Exception for lead processing errors
 */
class LeadProcessingException extends Exception
{
    private array $details;

    public function __construct(string $message = "", array $details = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

}
