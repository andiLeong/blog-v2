<?php

namespace App\Models;

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

//        \DB::listen(function ($query){
//            dump($query->sql);
//            dump($query->bindings);
//        });

        return [
//            'user_id' => [],
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
            'number' => [],
//            'username' => [
//                'column' => 'user_name',
//                'operator' => 'like',
//            ],
//            'country' => [
//                'value' => 'us',
//            ],
//            'greater_than_team_id' => [
//                'column' => 'user_name',
//                'operator' => '>=',
//            ]
        ];

//        return collect([
//            'order_by' => new OrderBy(),
//            'customer' => new Customer(),
//            'country' => new Country(),
//            'number' => new Number(),
//            'latest' => new Latest(),
//        ]);
    }

}
