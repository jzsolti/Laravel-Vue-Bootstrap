<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'App\Http\Controllers\SpaAuth\RegisterController@register');
Route::post('login', 'App\Http\Controllers\SpaAuth\LoginController@login');
Route::post('logout', 'App\Http\Controllers\SpaAuth\LoginController@logout');
Route::post('user/status', 'App\Http\Controllers\SpaAuth\LoginController@loggedIn');
Route::post('verify/email', 'App\Http\Controllers\SpaAuth\LoginController@verifyEmail');
Route::post('password/send-resetlink-email', 'App\Http\Controllers\SpaAuth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/confirm', ' App\Http\Controllers\SpaAuth\ForgotPasswordController@confirm');
Route::post('password/reset-password', 'App\Http\Controllers\SpaAuth\ForgotPasswordController@resetPassword');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
