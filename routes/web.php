<?php

use Illuminate\Support\Facades\Route;

Route::get('/{vue_capture?}', function() {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

// Transaction routes
Route::get('/api/transactions', 'TransactionController@index');
Route::post('/api/transactions', 'TransactionController@store');
Route::put('/api/transactions/{id}', 'TransactionController@update');
Route::delete('/api/transactions/{id}', 'TransactionController@destroy');
