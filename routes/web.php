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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');

Route::group(['prefix'=>'admin', 'middleware'=>['auth', 'role:admin']], function () {
	Route::resource('authors', 'AuthorsController');
	Route::resource('journals', 'JournalsController');
	Route::resource('profiles', 'JournalsController');
});

Route::group(['prefix'=>'menu', 'middleware'=>['auth']], function () {
	Route::get('profiles/update', 'ProfilesController@create');
	Route::post('profiles/update', 'ProfilesController@store');

	Route::get('educations/update', 'EducationsController@index');
	Route::get('educations/country', 'EducationsController@country');
	Route::post('educations/update', 'EducationsController@store');
});