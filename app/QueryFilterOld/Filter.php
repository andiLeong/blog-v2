<?php

namespace App\QueryFilterOld;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{

    protected function getFilterName()
    {
        return Str::snake(class_basename( $this ));
    }


    abstract public function apply(Builder $builder);
}
