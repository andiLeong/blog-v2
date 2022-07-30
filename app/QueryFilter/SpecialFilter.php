<?php

namespace App\QueryFilter;


class SpecialFilter
{

    public $clauses = ['whereIn', 'whereBetween'];
    /**
     * @var QueryArgumentPhaser
     */
    private $parser;
    private $query;
    /**
     * @var array
     */
    private $option;

    /**
     * WhereFilter constructor.
     * @param $query
     * @param QueryArgumentPhaser $parser
     */
    public function __construct($query,QueryArgumentPhaser $parser)
    {
        $this->parser = $parser;
        $this->query = $query;

        $this->option = $this->parser->getOption();
    }

    public function filter()
    {
        if ($this->shouldFilter()) {
            $clause = $this->clause();
            $this->query->$clause(
                $this->parser->column, $this->parser->value
            );
        }
        return $this->query;
    }

    public function shouldFilter()
    {
        return isset($this->option['clause']) && in_array($this->option['clause'],$this->clauses);
    }

    public function clause()
    {
        return $this->option['clause'];
    }
}
