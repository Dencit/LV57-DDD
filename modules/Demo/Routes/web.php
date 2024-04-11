<?php

/**
 * notes: 模块WEB路由
 */

use Illuminate\Support\Facades\Route;

Route::prefix('demo')->group(function() {
    Route::get('/', 'DemoController@index');
});
