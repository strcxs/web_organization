<?php

use App\Http\Controllers\LoginController;
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

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard/discuss', function () {
    return view('discuss');
})->name('discuss');

Route::get('/dashboard/announcement', function () {
    return view('announcement');
})->name('announcement');

Route::get('/dashboard/members', function () {
    return view('members');
})->name('members');

Route::get('/dashboard/vote', function () {
    return view('voting');
})->name('voting');

Route::get('/dashboard/vote/view', function () {
    return view('visimisi');
})->name('visimisi');

Route::get('/dashboard/voting', function () {
    return view('voteManage');
})->name('voteManage');



Route::get('/dashboard/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/dashboard/profile/detail', function () {
    return view('profile_detail');
})->name('profile_detail');

Route::get('/', function () {
    return view('web');
});

// Route::apiResource("/xxx","App\Http\Controllers\\LoginController");
// Route::apiResource("/xxx","App\Http\Controllers\\AngController");

// Route::get("/",[AngController::class,'show']);
// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
