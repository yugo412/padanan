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

Route::any('/', 'IndexController')->name('index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/cari', 'WordController@search')->name('word.search');
Route::get('/tambah', 'WordController@create')->name('word.create');
Route::post('/tambah', 'WordController@store');
Route::get('/{category}', 'WordController@category')->name('word.category');

Route::get('/kategori', 'CategoryController@index')->name('category.index');
