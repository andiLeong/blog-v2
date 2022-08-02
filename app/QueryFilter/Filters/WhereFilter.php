<?php

namespace App\QueryFilter\Filters;

use App\QueryFilter\QueryArgumentPhaser;

class WhereFilter
{

    /**
     * WhereFilter constructor.
     * @param $query
     * @param QueryArgumentPhaser $parser
     */
    public function __construct(private $query, private QueryArgumentPhaser $parser)
    {
        //
    }

    /**
     * apply filter to the query
     *
     * @return mixed
     */
    public function filter()
    {
        if ($this->shouldFilter()) {
            $this->query->where(
                $this->parser->column, $this->parser->operator, $this->parser->value
            );
        }
        return $this->query;
    }

    /**
     * decide if we should filter based on the option
     *
     * @return bool
     */
    public function shouldFilter()
    {
        $option = $this->parser->getOption();
        return !isset($option['clause']);
    }
}
