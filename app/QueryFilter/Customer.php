<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

class Customer extends Filter
{
    public function apply(Builder $query)
    {
        return $query->where($this->getFilterName(), 'like', request($this->getFilterName()) );
    }
}