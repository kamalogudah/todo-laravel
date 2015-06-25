<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'ProjectsController@index');
 
Route::get('home', 'HomeController@index');

Route::get('/register', function()
{
	return View::make('register');
});

Route::post('/register', function()
{
	$user = new \App\AppUser;
	$user->email = Input::get('email');
	$user->username = Input::get('username');
	$user->password = Hash::make(Input::get('password'));
	$user->save();
	$theEmail = Input::get('email');
	return View::make('thanks')->with('theEmail', $theEmail);
});

Route::get('/login', function()
{
	return View::make('login'); 
});

Route::post('/login', function()
{
	// $credentials = Input::only('username','password');
	if (Auth::attempt(array(
		'username' => $username,
		'password' => $password))) {
		return Redirect::intended('/');
	}

	return Redirect::to('login');
});

Route::get('/logout', function()
{
	Auth::logout();
	return View::make('logout');
});

Route::get('spotlight', array(
	'before' => 'auth', 
	function()
{
	return View::make('spotlight');
}));
//
Route::controllers([
'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
// Provide controller methods with object instead of ID
Route::model('tasks', 'Task');
Route::model('projects', 'Project');
Route::model('users', 'User');

 
// Use slugs rather than IDs in URLs
Route::bind('tasks', function($value, $route) {
	return App\Task::whereSlug($value)->first();
});
Route::bind('projects', function($value, $route) {
	return App\Project::whereSlug($value)->first();
});
 
Route::resource('projects', 'ProjectsController');
Route::resource('projects.tasks', 'TasksController');
Route::resource('auth', 'Auth/AuthController');