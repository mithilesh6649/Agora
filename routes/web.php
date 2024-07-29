<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SendNotificatoin;
use App\Http\Controllers\MeetingController;
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

Route::get('/pusher', function () {
    return view('pusher');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/send', [App\Http\Controllers\SendNotificatoin::class, 'index']);


Route::controller(MeetingController::class)->group(function () {
    Route::post('/create-meeting', 'createMeeting')->name('create.meeting');
    Route::get('/meeting/{url}', 'joinMeeting')->name('join.meeting');
});
