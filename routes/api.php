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

// auth routes
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('signup', 'AuthController@register');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::middleware(['auth:api'])->group(function () {
    //routes for posts
    Route::group([
        'prefix'=>'post'
    ],function(){
        Route::post('/', 'PostController@create');
        Route::delete('/{id}', 'PostController@delete');
        Route::get('/', 'PostController@posts');
    });
      //routes for comments
    Route::group([
        'prefix' => 'comment'
    ], function () {
        Route::post('/', 'CommentController@create');
        Route::delete('/{id}', 'CommentController@delete');
        Route::get('/{id}', 'CommentController@postComments');
    });
});
