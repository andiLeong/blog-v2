<?php

namespace App\Models;

use App\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
//    public function scopeFilters(Builder $query,array $request = null)
//    {
//        if(!method_exists($this,'getFilter')){
//            return $query;
//        }
//
//        $request = collect($request ?? request()->all())->filter();
//        $this->getFilter()->intersectByKeys($request)->each->apply($query);
//
//        return $query;
//    }

    public function scopeFilters(Builder $query,array $request = null)
    {
        if(!method_exists($this,'getFilter')){
            return $query;
        }

        $request = collect($request ?? request()->all())->filter();
        $filterOption = collect($this->getFilter())->intersectByKeys($request);

//        dd(collect(request()->all())->filter());
//        $filterOption = $this->getFilter();
        $filters = new QueryFilter($query,$filterOption);
        $filters->apply();
//        if(!method_exists($this,'getFilter')){
//            return $query;
//        }
//
//        $request = collect($request ?? request()->all())->filter();
//        $this->getFilter()->intersectByKeys($request)->each->apply($query);
//
//        return $query;
    }

}
