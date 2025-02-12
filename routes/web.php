<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

if (env('APP_ENV') !== 'local') {
    URL::forceScheme('https');
}

Route::get('/', function () {
    return view('welcome');
});
