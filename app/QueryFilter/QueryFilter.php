<?php

namespace App\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QueryFilter
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

    public function apply()
    {
        $this->filtersOption()
            ->filter(fn($filter) => is_array($filter))
            ->each(fn($filterOption, $key) => $this->attachQuery($filterOption, $key));
    }

    public function attachQuery($option, $key)
    {
        if (count($option) === 0) {
            return $this->query->where($key, request($key));
        }

        $parser = new QueryArgumentPhaser($option, $key);
        return $this->query->where($parser->column, $parser->operator, $parser->value);
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
