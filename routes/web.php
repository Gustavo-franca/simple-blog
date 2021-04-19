<?php

use Illuminate\Support\Facades\Route;
use ArticleController;
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
   return view('home');
  });

Route::get('/home', ['as' => 'home', 'uses' => 'App\Http\Controllers\HomeController@index'])->name('home');
Route::get('/articles', 'App\Http\Controllers\ArticleController@index')->name('articles');
//authentication
// Route::resource('auth', 'Auth\AuthController');
// Route::resource('password', 'Auth\PasswordController');
Route::get('/logout', 'App\Http\Controllers\UserController@logout');
Route::group(['prefix' => 'auth'], function () {
  Auth::routes();
});

Route::middleware(['auth'])->group(function () {
    // show new post form
    Route::get('new-post', 'App\Http\Controllers\ArticleController@create');
    // save new post
    Route::post('new-post', 'App\Http\Controllers\ArticleController@store');
    // edit post form
    Route::get('edit/{slug}', 'App\Http\Controllers\ArticleController@edit');
    // update post
    Route::post('update', 'App\Http\Controllers\ArticleController@update');
    // delete post
    Route::get('delete/{id}', 'App\Http\Controllers\ArticleController@destroy');
    // display user's all posts
    Route::get('my-all-posts', 'App\Http\Controllers\UserController@user_posts_all');
    // display user's drafts
    Route::get('my-drafts', 'App\Http\Controllers\UserController@user_posts_draft');
  });
  
  //users profile
  Route::get('user/{id}', 'App\Http\Controllers\UserController@profile')->where('id', '[0-9]+');
  // display list of posts
  Route::get('user/{id}/posts', 'App\Http\Controllers\UserController@user_posts')->where('id', '[0-9]+');
  // display single post
  Route::get('/{slug}', ['as' => 'post', 'uses' => 'App\Http\Controllers\ArticleController@show'])->where('slug', '[A-Za-z0-9-_]+');
  