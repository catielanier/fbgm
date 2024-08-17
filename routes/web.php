<?php

use Illuminate\Support\Facades\Route;

// Serve vue app
Route::get('/{vue_capture?}', function() {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');

// Transaction routes
Route::get('/api/transactions', 'TransactionController@index');
Route::post('/api/transactions', 'TransactionController@store');
Route::get('/api/transactions/{id}', 'TransactionController@show');
Route::put('/api/transactions/{id}', 'TransactionController@update');
Route::delete('/api/transactions/{id}', 'TransactionController@destroy');

// User routes
Route::post('/api/user/login', 'UserController@login');
Route::post('/api/user/register', 'UserController@register');
Route::delete('/api/user/delete', 'UserController@destroy');
