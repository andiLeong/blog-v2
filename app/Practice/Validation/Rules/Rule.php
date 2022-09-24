<?php

namespace App\Practice\Validation\Rules;


use ReflectionClass;

abstract class Rule
{
    protected $value;
    protected $arguments;
    protected $key;

    public function __construct($key, $value, $arguments = [])
    {
        $this->value = $value;
        $this->arguments = $arguments;
        $this->key = $key;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function key()
    {
       return $this->key;
    }

    public function getBaseName()
    {
        if ((new ReflectionClass($this))->isAnonymous()) {
            return 'closure';
        }

        return strtolower(class_basename($this));
    }

    abstract public function check(): bool;
    abstract public function message(): string;
}
