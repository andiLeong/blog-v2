<?php

namespace App\Models;

use App\QueryFilter\Customer;
use App\QueryFilter\Latest;
use App\QueryFilter\Number;
use App\QueryFilter\OrderBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function getFilter() :Collection
    {
        return collect([
            'order_by' => new OrderBy(),
            'customer' => new Customer(),
            'number' => new Number(),
            'latest' => new Latest(),
        ]);
    }

}