<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::apiResources([
        '/login' => 'LoginController',
        '/regis' => 'RegisController',
        '/data' => 'AngController',
        '/forum' => 'ForumController',
        '/announcement' => 'AnnController',
        '/connection' => 'ConnectionController',
        '/program' => 'ProgramController',
        '/divisi' => 'DivisiController',
        '/csv' => 'CsvController',
        '/member' => 'MemberController',
        '/username' => 'UserController',
        '/candidate' => 'CandidateController',
        '/vote' => 'VoteController',
        '/ballot' => 'BallotController',
        '/team' => 'TeamController',
        '/detail' => 'DetailController',
    ]);

    Route::post('/data/{id}', 'AngController@update');
    Route::delete('/data/{id}', 'AngController@destroy');
    Route::delete('/forum/{id}', 'ForumController@destroy');
    Route::post('/announcement', 'AnnController@store');
    Route::delete('/announcement/{id}', 'AnnController@destroy');
    Route::get('/comment/{id}', 'ComController@show');
    Route::post('/comment', 'ComController@store');
    Route::delete('/comment/{id}', 'ComController@destroy');
    Route::get('/topic', 'TopicController@index');
    Route::delete('/topic/{id}', 'TopicController@destroy');
    Route::post('/topic', 'TopicController@store');
    Route::post('/topic/{id}', 'TopicController@update');
});
