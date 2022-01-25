<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

class Number extends Filter
{
    public function apply(Builder $query)
    {
        return $query->where($this->getFilterName(), request($this->getFilterName()) );
    }
}