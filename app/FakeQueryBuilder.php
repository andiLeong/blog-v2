<?php

namespace App;


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
        if (func_num_args() === 2) {
            [$value, $operator] = [$operator, '='];
        }

        if (is_null($value)) {
            return $this->whereNull($column, $boolean );
        }

        $this->wheres[] = [
            'operator' => $operator,
            'column' => $column,
            'boolean' => $boolean,
            'value' => $value,
            'type' => 'Basic',
        ];
        $this->bindings['where'][] = $value;


        return $this;

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

}
