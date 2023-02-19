<?php

namespace App\QueryFilterOld;

use Illuminate\Database\Eloquent\Builder;

class Latest extends Filter
{
    public function apply(Builder $query)
    {
        return !is_null(request('order_by')) ? $query : $query->latest('id') ;
    }
}
