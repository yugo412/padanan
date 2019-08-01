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
Route::get('/keluar', 'Auth\LoginController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/tentang', 'AboutController')->name('about');

Route::get('/cari', 'WordController@search')->name('word.search');
Route::get('/tambah', 'WordController@create')->name('word.create');
Route::post('/tambah', 'WordController@store');
Route::get('/kata', 'WordController@index')->name('word.index');

Route::get('/kategori', 'CategoryController@index')->name('category.index');

Route::post('/{word}/suka', 'WordController@love')->name('word.love');
Route::get('/bidang/{category}', 'WordController@category')->name('word.category');
Route::get('/{word}', 'WordController@show')->name('word.show');
