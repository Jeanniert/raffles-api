<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaffleController;


Route::prefix('raffles/')->group(function(){   
    
    Route::get('index', [RaffleController::class, 'index']);
    Route::post('sales', [RaffleController::class, 'store']);
    Route::put('update/{id}', [RaffleController::class, 'update']);
    Route::delete('delete/{id}', [RaffleController::class, 'destroy']);
    Route::get('balance', [RaffleController::class, 'balance']);
    Route::get('validate', [RaffleController::class, 'numberValidate']);

});
