<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/brands', 'BrandsController@items')->name('filters');
Route::get('/filters', 'HomeController@filters')->name('filters');
Route::get('/filtered-mobiles', 'HomeController@filteredMobiles')->name('filteredMobiles');
Route::get('/news', 'NewsController@newsItems')->name('newsItems');

Route::get('/popular-comparisons', 'HomeController@getpopularcomparisons')->name('popular.comparisons');
Route::get('/compare-count', 'HomeController@compare')->name('compare.count');
Route::get('/mobile-regions', 'MobileRegionsController@items')->name('compare.count');

Route::get('/mobiles/{mobileId}/reviews', 'UserOpinionsController@ratingItems')
    ->name('ratingItems');
Route::post('/mobiles/{mobileId}/reviews', 'UserOpinionsController@saveReview')
    ->name('saveReview')
    ->middleware('auth:api');

Route::post('/register', 'Auth\ApiAuthController@register');
Route::post('/login', 'Auth\ApiAuthController@login');
Route::post('/users/{userId}/update', 'Auth\ApiAuthController@updateProfile')->middleware('auth:api');
