<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('member.dashboard');
});

require __DIR__ . '/member.php';
