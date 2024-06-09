<?php

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Laravel\Vapor\Http\Controllers\SignedStorageUrlController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group(['middleware' => 'guest:api', 'namespace' => 'Auth'], function () {

    if(config('auth.login_enabled')){
        Route::post('login', 'LoginController@login');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'ResetPasswordController@reset');

        Route::post('email/verify/{user}', 'VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'VerificationController@resend');
    }

    Route::post('register', 'RegisterController@register');

    Route::post('oauth/{driver}', 'OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'OAuthController@handleProviderCallback')->name('oauth.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::post('user-agreements', 'Auth\UserAgreementController@store')->name('agreements.create');

    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });

    if(config('auth.login_enabled')){
        Route::patch('settings/profile', 'Settings\ProfileController@update');
        Route::patch('settings/password', 'Settings\PasswordController@update');
    }

    /*
    |--------------------------------------------------------------------------
    | Accepted Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'accepted'], function () {
        Route::post('image', 'ImageController@store')->name('image.create');
        Route::delete('image', 'ImageController@destroy')->name('image.destroy');

        Route::group(['namespace' => 'Account'], function () {
            Route::resource('accounts', 'AccountController');
            Route::get('accounts/{account}/verify', 'VerificationController@show')->name('accounts.challenge');
            Route::post('accounts/{account}/verify', 'VerificationController@store')->name('accounts.verify');
            Route::post('accounts/{account}/domain', 'DomainController@store')->name('accounts.domain');
        });

        Route::group(['namespace' => 'Asset'], function () {
            Route::resource('assets', 'AssetController');
            Route::patch('assets/{asset}/approval', 'ApprovalController@update')->name('assets.approval');
            Route::patch('assets/{asset}/regulated', 'RegulatedController@update')->name('assets.regulated');
            Route::patch('assets/{asset}/anchored', 'AnchoredController@update')->name('assets.anchored');
            Route::patch('assets/{asset}/anchor', 'AnchorController@update')->name('assets.anchor');
            Route::patch('assets/{asset}/metrics', 'MetricsController@update')->name('assets.metrics');
        });

        Route::group(['namespace' => 'Organization'], function () {
            Route::resource('organizations', 'OrganizationController');
            Route::get('organizations/{organization}/toml', 'TomlController@show')->name('organizations.toml');
            Route::post('organizations/{organization}/link', 'LinkResourceController@store')->name('organizations.link');
            Route::delete('organizations/{organization}/link', 'LinkResourceController@destroy')->name('organizations.unlink');
            Route::post('organizations/{organization}/publish', 'PublishController@store')->name('organizations.publish');
            Route::patch('organizations/{organization}/documentation', 'DocumentationController@update')->name('organizations.documentation');
            Route::delete('organizations/{organization}/publish', 'PublishController@destroy')->name('organizations.unpublish');
        });

        Route::group(['namespace' => 'Principal'], function () {
            Route::resource('principals', 'PrincipalController');
        });

        Route::group(['namespace' => 'Validator'], function () {
            Route::resource('validators', 'ValidatorController');
        });
    });
});
