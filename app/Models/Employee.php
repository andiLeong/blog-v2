<?php

namespace App\Models;

use App\Models\Employee\Benefit\EmployeeBenefit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function benefit(EmployeeBenefit $benefit)
    {
        return $benefit->calculate($this);
    }
}
