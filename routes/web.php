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
	if (!Auth::guest()) {
		return redirect()->action('HomeController@index');
	}

    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::post('/changePassword', 'HomeController@changePassword');
Route::get('/getUserInfo', 'HomeController@getUserInfo');
Route::post('/updateUserInfo', 'HomeController@updateUserInfo');
//Route::get('/', 'HomeController@index');

Route::group(['prefix'=>'admin', 'middleware'=>['auth', 'role:admin']], function () {
	Route::get('users/roles', 'Admin\UsersController@roles');
	Route::resource('users', 'Admin\UsersController');

	// Route::resource('authors', 'AuthorsController');
	//	Route::resource('journals', 'JournalsController');
	//	Route::resource('profiles', 'JournalsController');
});

Route::group(['prefix'=>'koordinator', 'middleware'=>['auth', 'role:koordinator']], function () {
	Route::post('students/bulk', 'Koordinator\StudentsController@bulk');
	Route::get('topics/dosen', 'Koordinator\TopicsController@dosen');
	Route::get('students/period', 'Koordinator\StudentsController@period');
	Route::resource('students', 'Koordinator\StudentsController');
	Route::resource('topics', 'Koordinator\TopicsController');
	// Route::resource('periods', 'Koordinator\PeriodsController');
});

Route::group(['prefix'=>'dosen', 'middleware'=>['auth', 'role:dosen']], function () {
	Route::post('topics/peminatRespond', 'Dosen\TopicsController@peminatRespond');
	Route::get('topics/peminat/{id}', 'Dosen\TopicsController@peminat');
 	Route::resource('topics', 'Dosen\TopicsController');
 	Route::resource('students', 'Dosen\StudentsController');
});

Route::group(['prefix'=>'student', 'middleware'=>['auth', 'role:student']], function () {

	Route::resource('groups', 'Student\GroupsController');

	Route::group(['middleware'=>['checkgroup']], function () {
		Route::get('topics/dosen', 'Koordinator\TopicsController@dosen');
		Route::get('bukubiru/getData', 'Student\BukuBiruController@getData');

		Route::resource('topics', 'Student\TopicsController');
		Route::resource('bukubiru', 'Student\BukuBiruController');
		Route::resource('proposals', 'Student\ProposalsController');
	});
});