<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
Route::view('allpost','/allpost');
Route::view('adduser','/adduser');
Route::view('signup','/signup');
