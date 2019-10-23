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

Route::get('/create-matches/{pages?}', 'ParserController@createMatches')->name('create');
Route::get('/', 'ParserController@show')->name('all');

Route::get('/match/{match_id}', 'ParserController@getMatch');
