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

Route::get('/',         'HomeController@show')->name('home');
Route::get('/login',    'OAuthController@login')->name('login');
Route::get('/success',  'OAuthController@success')->name('login-success');
Route::get('/error',    'OAuthController@error')->name('login-error');

Route::get('/profile',  'SocialaccountController@showProfile')->name('profile')->middleware('auth');

