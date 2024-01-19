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
        Route::get('teacher', 'TeacherController@getTeacher');
    });

    Route::get('question/gets/{id_teacher}', 'Question\QuestionController@getQuestions');
    Route::post('question/add', 'Question\QuestionController@addQuestion');
    Route::group(['namespace' => 'Course', 'prefix' => 'course'], function () {

        Route::post('add', 'CourseController@addCourse');
        Route::get('gets', 'CourseController@getCourses');
        Route::group(['namespace' => 'Topic', 'prefix' => 'topic'], function () {
            Route::post('add', 'TopicController@addTopic');
            Route::get('gets/{id_course}', 'TopicController@getTopics');

            Route::get('gets/detail/{id_topic}', 'TopicController@getDetailInTopic');

            Route::group(['namespace' => 'Exam', 'prefix' => 'exam'], function () {
                Route::post('add', 'ExamController@addExam');
                Route::get('gets/{id_topic}', 'ExamController@getExams');
                Route::get('get/{id_exam}', 'ExamController@getExam');

                Route::group(['namespace' => 'Question', 'prefix' => 'question'], function () {
                    Route::post('add', 'ExamQuestionController@addExamQuestion');
                    Route::get('gets/{id_exam}', 'ExamQuestionController@getExamQuestions');
                });

            });

            Route::group(['namespace' => 'Video', 'prefix' => 'video'], function () {
                Route::post('add', 'VideoController@addVideo');
                Route::get('gets/{id_topic}', 'VideoController@getVideos');
                Route::get('get/{id_video}', 'VideoController@getVideo');
            });

            Route::group(['namespace' => 'Doc', 'prefix' => 'doc'], function () {
                Route::post('add', 'DocController@addDoc');
                Route::get('gets/{id_topic}', 'DocController@getDocs');
                Route::get('get/{id_doc}', 'DocController@getDoc');
            });
        });
    });
});

