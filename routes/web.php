<?php

use Illuminate\Support\Facades\Auth;

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


//Login routes
Route::get('/', function () {

    $user = Auth::user();
    if ($user->isAdmin())
        {
            return redirect('/admin');
        }
        return redirect('/home'); 
           
})->middleware('auth');

Auth::routes();

Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

//Admin routes
Route::get('/admin', 'AdminController@index')->name('admin');
Route::patch('/admin/{admin}/activateUser', 'AdminController@activateUser')->name('admin.activate');
Route::resource('admin', 'AdminController');

//User routes
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('home', 'HomeController');

//Temperature routes
Route::post('/temperature/getAllTemperatures', 'TemperatureController@getAllTemperatures')->name('temperature.all_temperatures');
Route::post('/temperature/getTemperature', 'TemperatureController@getTemperature')->name('temperature.current_temp');
Route::post('/temperature/saveTemperature', 'TemperatureController@saveTemperature')->name('temperature.save_temp');
Route::resource('temperature', 'TemperatureController');



