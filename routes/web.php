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

Route::get('/ekivalensi', function () {
	if (!Auth::guest()) {
		return view('ekuivalensi');
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
	Route::resource('periods', 'Koordinator\PeriodsController');
	Route::resource('groups', 'Koordinator\GroupsController');
});

Route::group(['prefix'=>'dosen', 'middleware'=>['auth', 'role:dosen']], function () {
	Route::post('topics/peminatRespond', 'Dosen\TopicssController@peminatRespond');
	Route::get('topics/peminat/{id}', 'Dosen\TopicssController@peminat');
 	Route::resource('topics', 'Dosen\TopicssController');
 	Route::resource('students', 'Dosen\StudentsController');

 	Route::get('bimbinganTA/regu/{id}', 'Dosen\BimbinganTAController@regu');
	Route::get('bimbinganTA/download', 'Dosen\BimbinganTAController@download');
 	Route::resource('bimbinganTA', 'Dosen\BimbinganTAController');

 	Route::get('proposals/download/{id}', 'Dosen\ProposalsController@download');
 	Route::resource('proposals', 'Dosen\ProposalsController');
});

Route::group(['prefix'=>'student', 'middleware'=>['auth', 'role:student']], function () {

	Route::resource('groups', 'Student\GroupsController');
	Route::get('graduation', 'Student\GraduationsController@index');

	Route::group(['middleware'=>['checkgroup']], function () {
		Route::get('topics/dosen', 'Koordinator\TopicsController@dosen');
		Route::get('bimbinganTA/download', 'Student\BimbinganTAController@download');

		Route::resource('topics', 'Student\TopicsController');
		Route::resource('bimbinganTA', 'Student\BimbinganTAController');
		Route::resource('proposals', 'Student\ProposalsController');
	});
});

Route::group(['prefix'=>'tu', 'middleware'=>['auth', 'role:administration']], function () {
	Route::resource('kpcorps', 'Tu\KPCorpsController');

	Route::get('transcripts/detailHistoris', 'Tu\TranscriptsController@detailHistoris');
	Route::get('transcripts/detail', 'Tu\TranscriptsController@detail');
	Route::get('courses/lookup', 'Tu\CoursesController@lookup');

	Route::post('transcripts/register', 'Tu\TranscriptsController@register');
	Route::post('courses/registerEquivalencies', 'Tu\CoursesController@registerEquivalencies');
	Route::resource('transcripts', 'Tu\TranscriptsController');
	Route::resource('courses', 'Tu\CoursesController');
});
