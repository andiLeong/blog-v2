<?php

use App\Models\Employee\Benefit\EmployeeBenefitFactory;
use Illuminate\Support\Facades\Route;

Route::get('/benefit', function (EmployeeBenefitFactory $benefit) {
    $employee = new \App\Models\Employee();
    dd($employee->benefit($benefit->make()));
});




Route::post('/login', \App\Http\Controllers\LoginController::class);
Route::post('/logout', \App\Http\Controllers\LogoutController::class);




