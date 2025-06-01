<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CorrectController;
use App\Http\Controllers\RestController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/email/verify', function () {
  return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  return redirect()->route('attendance.index');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();

  return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
  Route::post('/attendance/start', [AttendanceController::class, 'workStart'])->name('attendance.start');
  Route::post('/attendance/end', [AttendanceController::class, 'workEnd'])->name('attendance.end');
  Route::post('/rest/start', [RestController::class, 'breakStart'])->name('rest.start');
  Route::post('/rest/end', [RestController::class, 'breakEnd'])->name('rest.end');
  Route::get('/attendance/list', [AttendanceController::class, 'showAttendanceList'])->name('attendance.showAttendanceList');
  Route::get('/attendance/{id}', [AttendanceController::class, 'showAttendanceDetail'])->name('attendance.showAttendanceDetail');
  Route::get('/stamp_correction_request/list', [CorrectController::class, 'showRequestList'])->name('attendance.showRequestList');
  Route::post('/stamp_correction_request/send', [CorrectController::class, 'storeCorrectRequest'])->name('attendance.storeCorrectRequest');
  Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->group(function (){
  Route::get('/login', [AdminController::class, 'createLoginForm'])->name('admin.login');
  Route::post('/login', [AdminController::class, 'login']);

  Route::middleware('admin')->group(function () {
    Route::get('/attendance/list', [AdminController::class, 'showAttendanceList'])->name('admin.showAttendanceList');
    Route::get('/staff/list', [AdminController::class, 'showStaffList'])->name('admin.showStaffList');
    Route::get('/attendance/staff/{id}', [AdminController::class, 'showStaffAttendance'])->name('admin.showStaffAttendance');
    Route::get('/attendance/{id}', [AdminController::class, 'showAttendanceDetail'])->name('admin.showAttendanceDetail');
    Route::post('/attendance/{id}', [AdminController::class, 'storeAttendanceCorrect'])->name('admin.storeAttendanceCorrect');
    Route::get('/stamp_correction_request/list', [AdminController::class, 'showRequestList'])->name('admin.showRequestList');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminController::class, 'showRequestDetail'])->name('admin.showRequestDetail');
    Route::post('/stamp_correction_request/approve/{attendance_correct_request}', [AdminController::class, 'storeCorrectApproval'])->name('admin.storeCorrectApproval');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
  });
});