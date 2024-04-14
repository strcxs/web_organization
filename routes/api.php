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

// Route::apiResource("/data",AngController::class);
Route::apiResource("/login","App\Http\Controllers\\LoginController");
Route::apiResource("/regis","App\Http\Controllers\\RegisController");

Route::apiResource("/data","App\Http\Controllers\\AngController");
Route::post("/data/{id}","App\Http\Controllers\\AngController@update");
Route::delete("/data/{id}","App\Http\Controllers\\AngController@destroy");

Route::apiResource("/member","App\Http\Controllers\\memberController");

Route::apiResource("/username","App\Http\Controllers\\UserController");

Route::apiResource("/forum","App\Http\Controllers\\forumController");
Route::delete("/forum/{id}","App\Http\Controllers\\forumController@destroy");

Route::apiResource("/announcement","App\Http\Controllers\\AnnController");
Route::post("/announcement","App\Http\Controllers\\AnnController@store");
Route::delete("/announcement/{id}","App\Http\Controllers\\AnnController@destroy");

Route::get("/comment/{id}","App\Http\Controllers\\comController@show");
Route::post("/comment","App\Http\Controllers\\comController@store");
Route::delete("/comment/{id}","App\Http\Controllers\\comController@destroy");

Route::apiResource("/voting","App\Http\Controllers\\VotingController");

Route::apiResource("/vote","App\Http\Controllers\\VoteController");

Route::get("/result/{id}","App\Http\Controllers\\ResultController@show");

Route::get("/check/{id}","App\Http\Controllers\\CheckController@show");
Route::post("/check","App\Http\Controllers\\CheckController@store");

Route::get("/topic","App\Http\Controllers\\TopicController@index");
Route::delete("/topic/{id}","App\Http\Controllers\\TopicController@destroy");
Route::post("/topic","App\Http\Controllers\\TopicController@store");
Route::post("/topic/{id}","App\Http\Controllers\\TopicController@update");

Route::apiResource("/connection","App\Http\Controllers\\ConnectionController");

Route::apiResource("/program","App\Http\Controllers\\ProgramController");

Route::apiResource("/divisi","App\Http\Controllers\\DivisiController");


Route::apiResource("/csv","App\Http\Controllers\\CsvController");
