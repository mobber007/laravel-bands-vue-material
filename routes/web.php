<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();



Route::group(['middleware' => 'auth'], function(){

	//Load app
	Route::get('/', function () {
		return view('layouts.app');
	});


	/**
	 * Resources
	 */
	Route::resource('/bands', 'BandController', ['except' => [
		'create', 'edit'
	]]);

	Route::resource('/albums', 'AlbumController', ['except' => [
		'create', 'edit'
	]]);

});

