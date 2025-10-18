<?php

use Illuminate\Support\Facades\Route;

Route::post('/status', function () {
    return response()->json(['status' => 'API is running']);
});

