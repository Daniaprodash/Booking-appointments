<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DoctorController;

// home page route
Route::get('/', [AppController::class, 'getIndexData'])->name('index');

// authenticaton routes
Route::controller(AuthController::class)->name('auth.')->group(function(){
    Route::get('/login' , 'showLogin')->name('login');
    Route::post('/login' , 'loginProcessing');
    Route::get('/register' , 'showRegister')->name('register');
    Route::post('/register' , 'registerProcessing');
    Route::post('/logout' , 'logout')->name('logout')->middleware('auth');
});

// routes محمية
Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [App\Http\Controllers\AppController::class, 'index'])->name('dashboard');
    Route::get('/doctorDashboard' , [App\Http\Controllers\AppController::class, 'doctorDashboard'])->name('doctorDashboard');
    Route::get('/doctor/profile/edit', [DoctorController::class, 'editProfile'])->name('doctor.profile.edit');
    Route::put('/doctor/profile', [DoctorController::class, 'updateProfile'])->name('doctor.profile.update');
    Route::post('/appointments', [App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{id}', [App\Http\Controllers\AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [App\Http\Controllers\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('/appointments/{id}/confirm', [App\Http\Controllers\AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::patch('/appointments/{id}/reject', [App\Http\Controllers\AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::post('/appointments/{id}/send-email', [App\Http\Controllers\AppointmentController::class, 'sendEmail'])->name('appointments.send-email');
    Route::delete('/appointments/{id}/delete', [App\Http\Controllers\AppointmentController::class, 'delete'])->name('appointments.delete');
    Route::get('/showMedicalRecords' , [AppController::class, 'showMedicalRecords'])->name('showMedicalRecords');
    Route::get('/doctor/patient/{patient}', [DoctorController::class, 'patientFile'])->name('patientFile');
    Route::get('/payment', [AppController::class, 'showPaymentPage'])->name('payment');
    Route::get('/settings', [AppController::class, 'showSettingsPage'])->name('settings');
    Route::delete('/testimonials/{id}', [App\Http\Controllers\TestimonialController::class, 'delete'])->name('testimonials.delete');
});

//ather routes
Route::get('/appsearch' , [AppController::class, 'appsearch'])->name('appsearch');
Route::post('/testimonials', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
