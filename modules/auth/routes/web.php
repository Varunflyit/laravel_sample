<?php

use Ecommify\Auth\Http\Controllers\OAuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {

    Route::get('/oauth/{provider}/callback', [OAuthController::class, 'handleProviderCallback']);
});

Route::group([], function () {

    Route::post('/oauth/{provider}/{company}', [OAuthController::class, 'getAuthenticationUrl']);
});
