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
Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap');
Route::get('sitemap-{category}.xml', 'SitemapController@word')->name('sitemap.word');

Auth::routes();
Route::group(['namespace' => 'Auth'], function (){
   Route::get('masuk', 'LoginController@showLoginForm')->name('login');
   Route::get('lupa-sandilewat', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
   Route::get('daftar', 'RegisterController@showRegistrationForm')->name('register');
   Route::get('keluar', 'LoginController@logout')->name('logout');
});


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/tentang', 'AboutController')->name('about');

Route::get('/cari', 'WordController@search')->name('word.search');
Route::get('/tambah', 'WordController@create')->name('word.create');
Route::post('/tambah', 'WordController@store');
Route::get('/kata', 'WordController@index')->name('word.index');

Route::get('/kategori', 'CategoryController@index')->name('category.index');

Route::get('/ringkasan-mingguan', 'SummaryController@weekly')->name('summary.weekly');

Route::post('/{word}/suka', 'WordController@love')->name('word.love');
Route::post('/{word}/lapor', 'WordController@report')
    ->name('word.report')
    ->middleware('auth');
Route::get('/bidang/{category}', 'WordController@category')->name('word.category');
Route::get('/{word}', 'WordController@show')->name('word.show');
