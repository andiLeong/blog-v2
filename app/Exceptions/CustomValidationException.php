<?php

namespace App\Exceptions;


use Throwable;

class CustomValidationException extends \Exception
{
    private $errors;

    public function __construct($errors, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function setErrors($errors)
    {
        return $this->errors = $errors;
    }
}
