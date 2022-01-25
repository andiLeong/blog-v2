<?php

namespace App\Models;

trait Filterable
{
    public function scopeFilters($query,array $request = null)
    {
        $request = collect($request ?? request()->all());
        if(!method_exists($this,'getFilter')){
            return $query;
        }
        $filters = $this->getFilter();

        if($filters->isEmpty()){
            return $query;
        }

        $request->filter()->only($filters->keys())->ifNotEmpty(function($collection) use($filters,$query){
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