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
        return view('home'); 
           
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

