<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{

    public function scopeFilters(Builder $query, array $request = null): Builder
    {
        $filters = new QueryFilterManager($query, $this->getFilter(), $request);
        return $filters->apply();
    }

    public function getFilter()
    {
        throw new \LogicException('Please implements getFilter method');
    }

    public function scopeOrderFilters(Builder $query): Builder
    {
        $orderFilters = new OrderQueryFilter($query, $this->getOrderFilter());
        return $orderFilters->apply();
    }

    public function getOrderFilter()
    {
        throw new \LogicException('Please implements getOrderFilter method');
    }
}
