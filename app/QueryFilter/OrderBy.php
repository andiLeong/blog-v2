<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

class OrderBy extends Filter
{
    public function apply(Builder $query)
    {
        $direction = reqeust('direction') === 'asc' ?? 'desc';
        return $query->where(request($this->getFilterName()) , $direction );
    }
}