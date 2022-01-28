<?php

namespace App\Models;

trait Filterable
{
    public function scopeFilters($query,array $request = null)
    {
        if(!method_exists($this,'getFilter')){
            return $query;
        }
        $filters = $this->getFilter();

        if($filters->isEmpty()){
            return $query;
        }

        $request = collect($request ?? request()->all());

        $request->filter()->only($filters->keys())->whenNotEmpty(function($collection) use($filters,$query){
            $filters->filter(function($value,$key) use($collection){
                return $collection->has($key);
            })
            ->values()
            ->each
            ->apply($query);
        });

        return $query;
    }

}
