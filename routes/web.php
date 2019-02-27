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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [
	'uses' => 'AdminController@admin',
	'middleware' => 'is_admin',
	'as' => 'admin.dashboard'
]);

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::post('/update',[
	'uses' => 'HomeController@updateDeviceStatus',
	'as' => 'update.status'
]);


Route::get('/home', [
	'uses' => 'HomeController@getDevices',
	'as' => 'get.devices'
]);

Route::post('/update-add-device', [
	'uses' => 'HomeController@addUpdateDevice',
	'as' => 'update.add.device'
]);

Route::get('/get-pages', [
	'uses' => 'HomeController@getPages',
	'as' => 'get.pages'
]);

Route::get('admin/order-by/{name?}', [
	'uses' => 'FilterController@filter',
	'as' => 'filter.devices'
]);
