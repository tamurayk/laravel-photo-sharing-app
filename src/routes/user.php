<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\User\IndexController::class)->name('index');

// \Illuminate\Routing\Router::auth で $this->get('login', 'Auth\LoginController@showLoginForm') のように指定されている為、namespace を補完してる
Route::namespace('App\Http\Controllers\User')->group(function () {
    /**
     * Auth
     *
     * generated by `php artisan ui bootstrap --auth`
     */
    Auth::routes([
        'register' => true,
        'reset' => false,
        'confirm' => false,
        'verify' => false,
    ]);

});

/**
 * If authenticated, redirect to RouteServiceProvider::HOME by guest middleware.
 * note: guest middleware = RedirectIfAuthenticated\App\Http\Middleware\RedirectIfAuthenticated (See: \App\Http\Kernel::$routeMiddleware)
 */
Route::middleware('guest:user')->group(function () {
    /**
     * OAuth Login
     */
    Route::get('login/{provider}', \App\Http\Controllers\User\Auth\LoginController::class . '@redirectToProvider')->name('oauth.login');
    Route::get('login/{provider}/callback', \App\Http\Controllers\User\Auth\LoginController::class . '@handleProviderCallback')->name('oauth.callback');
});

/**
 * Require user auth
 */
Route::middleware('auth:user')->group(function () {

    Route::get('home', \App\Http\Controllers\User\Home\HomeIndexController::class)->name('home.index');

    Route::get('create', \App\Http\Controllers\User\Post\PostCreateController::class)->name('post.create');
    Route::post('create', \App\Http\Controllers\User\Post\PostStoreController::class)->name('post.store');
});

/**
 * Not Require user auth
 */
Route::get('{userName}', \App\Http\Controllers\User\Post\PostIndexController::class)->name('post.index');
