<?php

namespace Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

class Validate
{
    protected $callback;
    protected $name;
    protected $rules;
    protected $message = [];
    protected $currentRule;

    public static function name($name)
    {
        return (new static)->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function against(array|string $rules)
    {
        $this->rules = is_string($rules) ? [$rules] : $rules;
        return $this;
    }

    public function through(callable $fn)
    {
        $this->callback = $fn;

        foreach ($this->rules as $key => $value) {

            [$method, $args] = $this->parseRule($key, $value);
            if (method_exists($this, $method)) {
                $this->{$method}(...$args);
            }

        }
    }

    /**
     * @throws \Exception
     */
    public function required()
    {
        $invalids = [
            [$this->name => null],
            [$this->name => ''],
            [$this->name => ' '],
        ];

        $this->currentRule = 'required';
        $this->trigger($invalids);
    }

    /**
     * @throws \Exception
     */
    public function string()
    {
        $invalids = [
            [$this->name => 9],
            [$this->name => ['foo']],
            [$this->name => true],
            [$this->name => UploadedFile::fake()->image('avtar.jpg')],
        ];

        $this->currentRule = 'string';
        $this->trigger($invalids);
    }

    /**
     * @throws \Exception
     */
    public function array()
    {
        $invalids = [
            [$this->name => 'foo'],
            [$this->name => 99],
            [$this->name => true],
            [$this->name => UploadedFile::fake()->image('avtar.jpg')],
        ];

        $this->currentRule = 'array';
        $this->trigger($invalids);
    }

    /**
     * @throws \Exception
     */
    public function max($length = 255)
    {
        $invalids = [
            [$this->name => Str::random($length + 1)],
        ];

        $this->currentRule = 'max';
        $this->trigger($invalids);
    }

    /**
     * @throws \Exception
     */
    public function unique($column, $model)
    {
        $model = create($model);
        $invalids = [
            [$this->name => $model->{$column}],
        ];

        $this->currentRule = 'unique';
        $this->trigger($invalids);
    }

    public function file()
    {
        $invalids = [
            [$this->name => null],
            [$this->name => ''],
            [$this->name => ['foo']],
            [$this->name => true],
        ];

        $this->currentRule = 'file';
        $this->trigger($invalids);
    }

    protected function parseRule($key, $value)
    {
        $needMessage = false;

        if (is_int($key)) {
            $rule = $value;
        } else {
            $rule = $key;
            $needMessage = true;
        }

        $rule = explode(':', $rule);
        if (count($rule) === 1) {
            $method = $rule[0];
        } else {
            $method = array_shift($rule);
            $args = $rule;
        }

        if ($needMessage) {
            $this->message[$method] = $value;
        }
        return [$method, $args ?? []];
    }

    /**
     * trigger the register callback with invalid attributes
     * @param $attributes
     * @throws \Exception
     */
    protected function trigger($attributes): void
    {
        if (!is_callable($this->callback)) {
            throw new \Exception('call back is not set');
        }

        foreach ($attributes as $attribute) {
            $this->assert(call_user_func($this->callback, $attribute));
        }
    }

    /**
     * make sure the endpoint's result that contains the validation error for the corresponding key
     * @param mixed $result
     * @return TestResponse|void|null
     */
    protected function assert(TestResponse $result)
    {
        if ($message = $this->getMessage()) {
            return $result->assertJsonValidationMessageFor($this->name, null, $message);
        }

        return $result->assertJsonValidationErrorFor($this->name);
    }

    /**
     * get the message of the current rule if it has any
     * @return mixed|void
     */
    protected function getMessage()
    {
        if (array_key_exists($this->currentRule, $this->message)) {
            return $this->message[$this->currentRule];
        }
    }

}
