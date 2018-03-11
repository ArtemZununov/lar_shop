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

Route::get('/', 'ParseController@index');
Route::post('/parse', 'ParseController@parse');
Route::get('/parse-requests', 'ParseController@list');
Route::get('/parse-requests/{id}', 'ParseController@view');
Route::post('/parse-requests/{id}/save', 'ParseController@save');
Route::post('/parse-requests/{id}/csv', 'ParseController@csv');
Route::post('/parse-requests/{id}/email', 'ParseController@email');
