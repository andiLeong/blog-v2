<?php

namespace App\Practice\Validation\Rules;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

abstract class Rule
{
//    protected $needRequestRules = [
//        'required_if'
//    ];

    protected mixed $value;

    public function __construct(
        protected $key,
        protected $data,
        protected $arguments = []
    )
    {
        $this->value = $this->getValue();
    }

    public function setProperty($data,$key)
    {
        $this->data = $data;
        $this->key = $key;
        $this->value = $this->getValue();

        return $this;
    }

//    public function setRequest($rule,Request $request): Rule
//    {
//        if($this->needRequestDependency($rule)){
//            $this->request = $request;
//        }
//
//        return $this;
//    }
//
//    public function needRequestDependency($name)
//    {
//        return in_array($name,$this->needRequestRules);
//    }

    public function key()
    {
        return $this->key;
    }

    public function getValue($key = null)
    {
        return Arr::get($this->data, $key ?? $this->key);
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
