<?php


namespace App\Models\Employee\Benefit;


use Exception;

class EmployeeBenefitFactory
{

    public function make(string $benefit = null)
    {
        $benefits = [
            'shopping_coupon' => ShoppingCouponBenefit::class,
            'sick_leave' => SickLeaveBenefit::class,
            'bonus' => BonusBenefit::class,
            'travel' => TravelBenefit::class,
        ];

        if(!$benefit){
            $benefit = request('benefit');
        }

        if(!array_key_exists($benefit,$benefits)){
            throw new exception('benefit not found');
        }
        return new $benefits[$benefit];
    }
}
