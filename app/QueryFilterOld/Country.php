<?php

namespace App\QueryFilterOld;

use Illuminate\Database\Eloquent\Builder;

class Country extends Filter
{
    public function apply(Builder $query)
    {
        return $query->where($this->getFilterName(), request($this->getFilterName()) );
    }
}
