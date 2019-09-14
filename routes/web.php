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
Route::get('sitemap-{category}.xml', 'SitemapController@term')->name('sitemap.term');

Route::get('kontak', 'ContactController@form')->name('contact');
Route::post('kontak', 'ContactController@store');

Auth::routes();
Route::group(['namespace' => 'Auth'], function (){
   Route::get('masuk', 'LoginController@showLoginForm')->name('login');
   Route::get('lupa-sandilewat', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
   Route::get('daftar', 'RegisterController@showRegistrationForm')->name('register');
   Route::get('keluar', 'LoginController@logout')->name('logout');
});


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/tentang', 'AboutController')->name('about');

Route::get('/cari', 'TermController@search')->name('term.search');
Route::get('/tambah', 'TermController@create')->name('term.create');
Route::post('/tambah', 'TermController@store');
Route::get('/istilah', 'TermController@index')->name('term.index');

Route::get('/bidang', 'CategoryController@index')->name('category.index');

Route::get('/ringkasan-mingguan', 'SummaryController@weekly')->name('summary.weekly');

Route::post('/{term}/suka', 'TermController@love')->name('term.love');
Route::post('/{term}/lapor', 'TermController@report')
    ->name('term.report')
    ->middleware('auth');
Route::get('/bidang/{category}', 'TermController@category')->name('term.category');
Route::get('/{term}', 'TermController@show')->name('term.show');
