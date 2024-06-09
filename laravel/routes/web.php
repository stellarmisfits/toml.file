<?php

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

if(config('app.env') === 'local') {
    Route::get('/clear', function () {
        return (string)opcache_reset();
    });
}

Route::group(['middleware' => 'cors'], function () {
    Route::get('federation', 'FederationController@index')->name('federation');
    Route::get('/toml/{key}/.well-known/stellar.toml', 'TomlController@show')->name('toml');
});

Route::group(['domain' => '{slug}.' . parse_url(config('app.url'))['host']], function() {
    Route::get('/.well-known/stellar.toml', 'TomlController@show');
    Route::get('{path}', function () {
        return redirect()->to(config('app.url'));
    })->where('path', '(.*)');
});

Route::get('{path}', function () {
    return view('index');
})->where('path', '^(?!api).*');
