<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/listEmployees', 'Employee\PageController@showEmployees');
Route::post('/searchEmployee', 'Employee\PageController@searchEmployee');

