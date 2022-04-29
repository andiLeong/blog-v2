<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilters(Builder $query,array $request = null)
    {
        if(!method_exists($this,'getFilter')){
            return $query;
        }

        $request = collect($request ?? request()->all())->filter();
        $this->getFilter()->intersectByKeys($request)->each->apply($query);

        return $query;
    }

}
