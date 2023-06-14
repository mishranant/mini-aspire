<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    // Loan routes
    Route::post('/loans', 'App\Http\Controllers\LoanController@store');
    Route::get('/loans/{id}', 'App\Http\Controllers\LoanController@show');
    
    // Repayment routes
    Route::post('/repayments', 'App\Http\Controllers\RepaymentController@store');
});
