<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomValidationException extends Exception
{
    protected $message;

    protected $code;

    /**
     * UsedInOtherTableException constructor.
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->code = empty($code) ? 422 : $code;

        if (empty($message)) {
            $this->message = 'Validation error';
        }
    }
}
