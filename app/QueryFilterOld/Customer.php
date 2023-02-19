<?php

namespace App\QueryFilterOld;

use Illuminate\Database\Eloquent\Builder;

class Customer extends Filter
{
    public function apply(Builder $query)
    {
        $name = request($this->getFilterName());
        return $query->where($this->getFilterName(), 'like', '%' .$name . '%');
    }
}
