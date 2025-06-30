<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MarkController;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/store', [AuthController::class, 'storeLogin'])->name('login.store');

Route::post('/register/post', [AuthController::class, 'storeRegister'])->name('register.store');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/admin-dashboard', [AuthController::class, 'admin'])->name('admin_dashborad');
    Route::get('/students/{id}', [AuthController::class, 'getStudent']);
    Route::put('/students/{id}', [AuthController::class, 'updateStudent']);
    Route::delete('/students/{id}', [AuthController::class, 'deleteStudent']);
    Route::get('/student/{id}/marks', [MarkController::class, 'index'])->name('student.marks');
    Route::post('/student/{id}/marks', [MarkController::class, 'store'])->name('student.marks.store');
    Route::delete('/marks/{id}', [MarkController::class, 'destroy'])->name('marks.destroy');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
