<?php

namespace App\QueryFilter\Filters;

use App\QueryFilter\QueryArgumentPhaser;

class WhereFilter
{
    /**
     * @var QueryArgumentPhaser
     */
    private $parser;
    private $query;


    /**
     * WhereFilter constructor.
     * @param $query
     * @param QueryArgumentPhaser $parser
     */
    public function __construct($query, QueryArgumentPhaser $parser)
    {
        $this->parser = $parser;
        $this->query = $query;
    }

    public function filter()
    {
        if ($this->shouldFilter()) {
            $this->query->where(
                $this->parser->column, $this->parser->operator, $this->parser->value
            );
        }
        return $this->query;
    }

    public function shouldFilter()
    {
        $option = $this->parser->getOption();
        return !isset($option['clause']);
    }
}
