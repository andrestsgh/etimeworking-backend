<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;

Route::post('login', [ApiAuthController::class, 'login']);

// Endpoints que requieren el token de autenticación
Route::middleware(['auth:api'])->group(function () {
    Route::get('employee/contracts/active', [EmployeeController::class, 'getEmployeeActiveContracts']);
    Route::get('employee/contracts', [EmployeeController::class, 'getEmployeeContracts']);
    Route::get('employee/contracts/{contract_id}/{start_date?}/{end_date?}/{records_per_page?}/{page?}', [EmployeeController::class, 'getContract']);
    Route::post('employee/records', [RecordController::class, 'storeRecord']);
});