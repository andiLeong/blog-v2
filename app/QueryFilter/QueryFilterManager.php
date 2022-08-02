<?php

namespace App\QueryFilter;

use App\QueryFilter\Filters\SpecialFilter;
use App\QueryFilter\Filters\WhereFilter;
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
    private $request;

    /**
     * @var array
     */
    private $filterOption;


    /**
     * QueryFilterManager constructor.
     * @param Builder $query
     * @param array $filterOption
     * @param array|null $request
     */
    public function __construct(Builder $query, array $filterOption, array $request = null)
    {
        $this->query = $query;
        $this->request = $request;
        $this->filterOption = $filterOption;
    }

    /**
     * apply each query cause to builder
     *
     * @return Builder
     */
    public function apply()
    {
        $this->getSharedOption()
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
     * get the both shared option key and request key
     * eg: when request have query string foo=bar&baz=1
     * but in our filter option we only key of foo
     * so we only response to foo filter
     *
     * @return Collection
     */
    public function getSharedOption(): Collection
    {
        return collect($this->filterOption)
            ->intersectByKeys($this->removeNullFromRequest());
    }

    /**
     *  remove any null value from the request
     *
     * @return array
     */
    public function removeNullFromRequest()
    {
        return array_filter(
            $this->request ?? request()->all(),
            fn($request) => !is_null($request)
        );
    }

}
