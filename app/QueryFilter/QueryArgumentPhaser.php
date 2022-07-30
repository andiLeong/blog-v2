<?php

namespace App\QueryFilter;

class QueryArgumentPhaser
{
    /**
     * @var array
     */
    private $option;

    private $defaultOperator = '=';
    private $supportedOperator = [
        '=', '>=', '<=', '>', '<', 'like'
    ];
    private $defaultColumn;

    /**
     * QueryArgumentPhaser constructor.
     * @param array $option
     * @param $defaultColumn
     */
    public function __construct(array $option, String $defaultColumn)
    {
        $this->option = $option;
        $this->defaultColumn = $defaultColumn;
    }


    private function getColumn()
    {
        if (array_key_exists('column', $this->option)) {
            return $this->option['column'];
        }
        return $this->defaultColumn;
    }


    private function getOperator()
    {
        if (array_key_exists('operator', $this->option)) {
            $operator = $this->option['operator'];
        } else {
            $operator = $this->defaultOperator;
        }
        $this->guardAgainOperator($operator);
        return $operator;
    }


    private function getValue()
    {
        if (array_key_exists('value', $this->option)) {
            $value = $this->option['value'];
        } else {
            $value = request($this->defaultColumn);
        }

        if ($this->getOperator() == 'like') {
            return '%' . $value . '%';
        }
        return $value;
    }

    public function guardAgainOperator(string $operator)
    {
        if (!in_array($operator, $this->supportedOperator)) {
            throw new \InvalidArgumentException('Query operator is not supported');
        }
    }

    public function __get($name)
    {
        $methond = "get" . ucfirst($name);
        if(!method_exists($this,$methond)){
            throw new \InvalidArgumentException("{$name} property not found.");
        }

        return $this->$methond();
    }


}
