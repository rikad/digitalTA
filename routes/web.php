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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
//Route::get('/', 'HomeController@index');

Route::group(['prefix'=>'admin', 'middleware'=>['auth', 'role:admin']], function () {
	Route::resource('users', 'Admin\UsersController');
	// Route::resource('authors', 'AuthorsController');
//	Route::resource('journals', 'JournalsController');
//	Route::resource('profiles', 'JournalsController');
});

Route::group(['prefix'=>'menu', 'middleware'=>['auth']], function () {
	Route::get('profiles/update', 'ProfilesController@create');
	Route::post('profiles/update', 'ProfilesController@store');

	Route::get('educations/update', 'EducationsController@index');
	Route::post('educations', 'EducationsController@store');
	Route::delete('educations/{id}', 'EducationsController@destroy');
	Route::get('educations/options', 'EducationsController@options');

	Route::get('experiences/update', 'ExperiencesController@index');
	Route::get('experiences/organizations', 'ExperiencesController@organizations');
	Route::post('experiences', 'ExperiencesController@store');
	Route::delete('experiences/{id}', 'ExperiencesController@destroy');

	Route::get('certifications/update', 'CertificationsController@index');
	Route::post('certifications', 'CertificationsController@store');
	Route::delete('certifications/{id}', 'CertificationsController@destroy');

	Route::get('memberships/update', 'MembershipsController@index');
	Route::post('memberships', 'MembershipsController@store');
	Route::delete('memberships/{id}', 'MembershipsController@destroy');

	Route::get('awards/update', 'AwardsController@index');
	Route::post('awards', 'AwardsController@store');
	Route::delete('awards/{id}', 'AwardsController@destroy');

	Route::get('activities/update', 'ActivitiesController@index');
	Route::post('activities', 'ActivitiesController@store');
	Route::delete('activities/{id}', 'ActivitiesController@destroy');

	Route::get('publications/update', 'PublicationsController@index');
	Route::get('publications/users', 'PublicationsController@users');
	Route::post('publications', 'PublicationsController@store');
	Route::get('publications/selectedUsers/{id}', 'PublicationsController@selectedUsers');
	Route::delete('publications/{id}', 'PublicationsController@destroy');

});