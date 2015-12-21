<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('stat', 'StatController@index');


Route::post('/wish/update', 'WishController@update');
Route::post('/wish/picked', 'WishController@picked');
Route::post('/wish/pick', 'WishController@pick');
Route::get('/wish/mylist', 'WishController@mylist');
Route::post('/wish/delete', 'WishController@delete');
Route::post('/wish/created', 'WishController@created');
Route::post('/wish/create', 'WishController@store');
Route::get('/wish', 'WishController@index');
Route::post('/user/edit', 'UserController@edit');
Route::post('/user/avatar', 'UserController@avatar');
Route::post('user/byhxids', 'UserController@byhxids');
Route::get('user', 'UserController@index');
Route::post('tag/delete', 'TagController@delete');
Route::post('tag/create', 'TagController@create');
Route::get('tag', 'TagController@index');
Route::get('/school', 'SchoolController@index');
Route::post('/resetpsw', 'UserController@findpsw');
Route::post('/register', 'AuthController@register');
Route::get('push/test', 'PushController@test');
Route::post('push', 'PushController@index');
Route::post('/profile', 'UserController@profile');
Route::post('/password/reset', 'UserController@resetpsw');
Route::get('/notice', 'NoticeController@index');
Route::post('/logout', 'AuthController@logout');
Route::post('/login', 'AuthController@login');
Route::post('/feedback', 'FeedbackController@store');
Route::get('/code', 'CodeController@index');
/*
Route::resource('accesstoken', 'AccesstokenController');
Route::resource('code', 'CodeController');
Route::resource('creator.wish', 'CreatorWishController');
Route::resource('notice', 'NoticeController');
Route::resource('picker.wish', 'PickerWishController');
Route::resource('school', 'SchoolController');
Route::resource('session', 'SessionController');
Route::resource('user', 'UserController');
Route::resource('wish', 'WishController');

*/
