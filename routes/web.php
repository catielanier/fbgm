<?php

use Illuminate\Support\Facades\Route;

Route::get('/{vue_capture?}', function() {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

Route::get('/api/transactions', 'TransactionController@index');
Route::post('/api/transactions', 'TransactionController@store');
