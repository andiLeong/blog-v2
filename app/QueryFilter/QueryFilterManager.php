<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QueryFilterManager
{

    /**
     * @var Builder
     */
    private $query;

    /**
     * @var array|null
     */
    private $filtersOption;


    public function __construct(Builder $query, array|Collection $filtersOption)
    {
        $this->query = $query;
        $this->filtersOption = $filtersOption;
    }

    /**
     * apply each query cause to builder
     *
     * @return Builder
     */
    public function apply()
    {
        $this->filtersOption()
            ->filter(fn($filter) => is_array($filter))
            ->each(fn($filterOption, $key) => $this->attachQuery($filterOption, $key));

        return $this->query;
    }

    /**
     * attach query to builder
     *
     * @param $option
     * @param $key
     * @return Builder
     */
    public function attachQuery($option, $key)
    {
        $parser = new QueryArgumentPhaser($option, $key);
        $filters = [
            SpecialFilter::class,
            WhereFilter::class
        ];

        collect($filters)
            ->map(fn($filter) => new $filter($this->query, $parser))
            ->each
            ->filter();

        return $this->query;
    }


    /**
     * normalize the filter option to a collection
     * @return Collection
     */
    public function filtersOption(): Collection
    {
        if ($this->filtersOption instanceof Collection) {
            return $this->filtersOption;
        }
        return collect($this->filtersOption);
    }


}
