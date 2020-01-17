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
Route::get('/', 'HomeController@home')->name('index');
Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
Route::post('/home/getmTicket', 'HomeController@getmTicket')->name('getmTicket');
Route::get('/home/createForm', 'HomeController@createForm');
Route::post('/home/createForm', 'HomeController@createForm');

Route::namespace('Auth')->group(function() {
	Route::get('/login', 'LoginController@show')->name('login');
	Route::post('/login', 'LoginController@login');
	Route::post('/logout', 'LoginController@logout')->name('logout');
});

// Route::get('login', 'Auth\LoginController@show');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout');