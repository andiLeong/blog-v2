<?php


namespace App\Models\Employee\Benefit;


use App\Models\Employee;

interface EmployeeBenefit
{

    public function calculate(Employee $employee);
}
