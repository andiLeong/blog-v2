<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

class OrderBy extends Filter
{
    public function apply(Builder $query)
    {
        $direction = request('direction') ?? 'desc';
        return $query->orderBy(request($this->getFilterName()) , $direction);
    }
}
