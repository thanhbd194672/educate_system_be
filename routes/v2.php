<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('auth/login', 'Auth\LoginController@index');
Route::post('register', 'Auth\RegisterController@index');

Route::middleware('auth.v2:sca')->group(function () {
    Route::group(['namespace' => 'Account', 'prefix' => 'account'], function () {
        Route::get('me', 'UserController@getMe');
    });
    Route::group(['namespace' => 'Course', 'prefix' => 'course'], function () {
        Route::post('add', 'CourseController@addCourse');
        Route::get('gets', 'CourseController@getCourses');
        Route::group(['namespace' => 'Topic', 'prefix' => 'topic'], function () {
            Route::post('add', 'TopicController@addTopic');
            Route::get('gets/{id_course}', 'TopicController@getTopics');
        });
    });
});

