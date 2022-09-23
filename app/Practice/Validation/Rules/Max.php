<?php

namespace App\Practice\Validation\Rules;

class Max extends Rule
{
    private $max;

    public function __construct($key, $value, $arguments)
    {
        parent::__construct($key, $value, $arguments);
        $this->max = $arguments[0];
    }

    public function check(): bool
    {
        return strlen($this->value) <= $this->max;
    }

    public function message(): string
    {
        return "The $this->key must not exceed $this->max long";
    }
}
