<?php

namespace App;


use Closure;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Arr;

class FakeQueryBuilder extends Builder
{

    public $columns = [];
    public $wheres = [];
    public $bindings = [
        'where' => [],
    ];

    public $from;
    public $connection;
    public $grammar;
    public $processor;

    public function __construct(
        ConnectionInterface $connection,
        Grammar $grammar = null,
        Processor $processor = null
    )
    {
        $this->connection = $connection;
        $this->grammar = $grammar ?: $connection->getQueryGrammar();
        $this->processor = $processor ?: $connection->getPostProcessor();
    }

    public function select($columns = ['*'])
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        foreach ($columns as $column) {
            $this->columns[] = $column;
        }

        return $this;
    }

    public function from($table, $as = null)
    {
        $this->from = $table;
        return $this;
    }

    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (is_array($column)) {
            foreach ($column as $col => $value) {
                $this
                    ->assignWheres($col, $value, '=', $boolean)
                    ->assignBindings($value);
            }
            return $this;
        }


        if ($column instanceof Closure && is_null($operator)) {
            $column($this);
            return $this;
        }

        if (func_num_args() === 2) {
            [$value, $operator] = [$operator, '='];
        }

        if (is_null($value)) {
            return $this->whereNull($column, $boolean);
        }


        $this
            ->assignWheres($column, $value, $operator, $boolean, 'Basic')
            ->assignBindings($value);


        return $this;

    }

    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $type = $not ? 'NotIn' : 'In';
        $this->wheres[] = compact( 'column', 'boolean', 'type', 'values');
        return $this
            ->assignBindings($values);
    }

    public function whereBetween($column, iterable $values, $boolean = 'and', $not = false)
    {
        $type = 'between';
        $this->wheres[] = compact( 'column', 'boolean', 'type', 'values');
        return $this
            ->assignBindings($values);
    }


    public function whereNull($columns, $boolean = 'and', $not = false)
    {
        $type = $not ? 'NotNull' : 'Null';

        foreach (Arr::wrap($columns) as $column) {
            $this->wheres[] = [
                'type' => $type,
                'column' => $column,
                'boolean' => $boolean,
            ];
        }

        return $this;
    }

    public function get($columns = ['*'])
    {
        if (count($this->columns) > 0) {
            $columns = $this->columns;
        } else {
            $columns = Arr::wrap($columns);
        }
        $this->columns = $columns;

//        dd($this);
//        dd($this->tosQl());
        $res = $this->connection->select(
            $this->toSql(),
            Arr::flatten($this->bindings)
        );
//        dd($res);
        return collect($res);
    }

    public function toSql()
    {
        return $this->grammar->compileSelect($this);
    }

    /**
     * @param $column
     * @param $value
     * @param string $operator
     * @param string $boolean
     * @param string $type
     * @return FakeQueryBuilder
     */
    protected function assignWheres($column, $value, $operator = '=', $boolean = 'and', $type = 'Basic'): FakeQueryBuilder
    {
        $this->wheres[] = compact('operator', 'column', 'boolean', 'type', 'value');
        return $this;
    }

    /**
     * @param $value
     * @param string $bind
     * @return FakeQueryBuilder
     */
    protected function assignBindings($value, $bind = 'where'): FakeQueryBuilder
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->bindings[$bind][] = $v;
            }
        } else {
            $this->bindings[$bind][] = $value;
        }

        return $this;
    }

    /**
     * @param array $parameters
     * @param string $method
     * @return FakeQueryBuilder
     */
    protected function dynamicWheres(array $parameters, string $method): FakeQueryBuilder
    {
        if (count($parameters) === 1) {
            $value = $parameters[0];
            $operator = '=';
        } else if (count($parameters) === 2) {
            $operator = $parameters[0];
            $value = $parameters[1];
        } else {
            throw new \InvalidArgumentException('only 2 arguments are needed.');
        }

        $column = substr($method, 5);

        return $this
            ->assignWheres($column, $value, $operator)
            ->assignBindings($value);
    }


    public function __call($method, $parameters)
    {
        if (str_starts_with($method, 'where')) {
            return $this->dynamicWheres($parameters, $method);
        }
    }
}
