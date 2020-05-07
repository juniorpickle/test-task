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

Route::get('/start', 'TaskController@task');
// Route::post('/start', 'TaskController@postTask')->name('ajax');

Route::get('/', function () {
    return view('welcome');
});
