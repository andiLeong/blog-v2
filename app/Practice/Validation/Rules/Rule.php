<?php

namespace App\Practice\Validation\Rules;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Rule
{
    protected Request $request;
    protected $needRequestRules = [
        'required_if'
    ];

    public function __construct(
        protected $key,
        protected $value,
        protected $arguments = []
    )
    {
        //
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

    public function setRequest($rule,Request $request): Rule
    {
        if($this->needRequestDependency($rule)){
            $this->request = $request;
        }

        return $this;
    }

    public function needRequestDependency($name)
    {
        return in_array($name,$this->needRequestRules);
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

        return strtolower(Str::snake(class_basename($this)));
    }

    abstract public function check(): bool;
    abstract public function message(): string;


}
