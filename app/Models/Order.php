<?php

namespace App\Models;

use App\Practice\Pagination\Paginator;
use App\QueryFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!array_key_exists('number', $order->attributes)) {
                $order->number = Str::random(40);
            }
        });
    }

    public function getFilter()
    {
        return [
            'number' => [],
            'country' => [],
            'customer' => [
                'operator' => 'like'
            ],
            'price_greater_than' => [
                'operator' => '>',
                'column' => 'price'
            ],
            'price_lesser_or_equal_than' => [
                'operator' => '<=',
                'column' => 'price'
            ],
            'price_lesser_than' => [
                'operator' => '<',
                'column' => 'price'
            ],
            'country_in' => [
                'clause' => 'whereIn',
                'column' => 'country'
            ],
            'country_not_in' => [
                'clause' => 'whereNotIn',
                'column' => 'country'
            ],
            'price_between' => [
                'clause' => 'whereBetween',
                'column' => 'price'
            ],
            'price_not_between' => [
                'clause' => 'whereNotBetween',
                'column' => 'price'
            ],
            'year_is' => [
                'clause' => 'whereYear',
                'column' => 'created_at'
            ],
            'day_is' => [
                'clause' => 'whereDay',
                'column' => 'created_at'
            ],
            'month_is' => [
                'clause' => 'whereMonth',
                'column' => 'created_at'
            ],
            'update_at_is_null' => [
                'clause' => 'whereNull',
                'column' => 'updated_at'
            ],
        ];
    }

    public function getOrderFilter()
    {
        return [
            'key' => 'order_by',
            'direction' => 'direction'
            // order_by[]=created_at&order_by[]=customer&direction[]=desc&direction[]=asc
        ];
    }

    public function paginator($perPage = null,$pageName = 'page')
    {
        $perPage ??= $this->getPerPage();
        $page =request()->has($pageName) ? request()->get($pageName) : 1 ;
        if(!is_numeric($page)){
           $page = 1;
        }
        $page = (int) max($page,1);

        $offset = $perPage * $page - $perPage;
        $orders = DB::select("select * from {$this->getTable()} limit $perPage offset $offset");
        $total = $this->count();

        return new Paginator($orders,$perPage,$total,$page,$pageName);
    }
}
