<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return abort(404);
})->name('login');
