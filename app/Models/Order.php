<?php

namespace App\Models;

use App\QueryFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->number = Str::random(40);
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
            'price_between' => [
                'clause' => 'whereBetween',
                'column' => 'price'
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
}
