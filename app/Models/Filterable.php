<?php

namespace App\Models;

use App\QueryFilter\OrderQueryFilter;
use App\QueryFilter\QueryFilterManager;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{

    public function scopeFilters(Builder $query, array $request = null): Builder
    {
        if (!method_exists($this, 'getFilter')) {
            return $query;
        }

        $request = collect($request ?? request()->all())->filter(fn($key) => !is_null($key));
        $filterOption = collect($this->getFilter())->intersectByKeys($request);

//        dd(collect(request()->all())->filter());
//        $filterOption = $this->getFilter();
        $filters = new QueryFilterManager($query, $filterOption);
        return $filters->apply();

//        if(!method_exists($this,'getFilter')){
//            return $query;
//        }
//
//        $request = collect($request ?? request()->all())->filter();
//        $this->getFilter()->intersectByKeys($request)->each->apply($query);
//
//        return $query;
    }

    public function scopeOrderFilters(Builder $query): Builder
    {
        if (!method_exists($this, 'getOrderFilter')) {
            return $query;
        }

        $orderFilters = new OrderQueryFilter($query,$this->getOrderFilter());
        return $orderFilters->apply();
    }
}
