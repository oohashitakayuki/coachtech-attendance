<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CorrectController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::middleware('auth')->group(function () {
  Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
  Route::post('/attendance/start', [AttendanceController::class, 'workStart'])->name('attendance.start');
  Route::post('/attendance/end', [AttendanceController::class, 'workEnd'])->name('attendance.end');
  Route::get('/attendance/list', [AttendanceController::class, 'attendance'])->name('attendance.attendance');
  Route::get('/stamp_correction_request/list', [CorrectController::class, 'showRequestList'])->name('attendance.showRequestList');
  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});