<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientProfileController;
use App\Http\Controllers\DoctorMedicalRecordController;
use App\Http\Controllers\DoctorVisitController;

// Admin routes
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\MedicalRecordController as AdminMedicalRecordController;

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
    Route::get('/patient/medical-record', [App\Http\Controllers\AppController::class, 'patientMedicalRecord'])->name('patient.medical-record');
    Route::get('/doctorDashboard' , [App\Http\Controllers\AppController::class, 'doctorDashboard'])->name('doctorDashboard');
    Route::get('/doctor/profile/edit', [DoctorController::class, 'editProfile'])->name('doctor.profile.edit');
    Route::put('/doctor/profile', [DoctorController::class, 'updateProfile'])->name('doctor.profile.update');
    Route::get('/patient/profile/edit', [PatientProfileController::class, 'edit'])->name('patient.profile.edit');
    Route::put('/patient/profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');
    Route::post('/appointments', [App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/available-slots', [App\Http\Controllers\AppointmentController::class, 'getAvailableSlots'])->name('appointments.available-slots');
    Route::put('/appointments/{id}', [App\Http\Controllers\AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [App\Http\Controllers\AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('/appointments/{id}/confirm', [App\Http\Controllers\AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::patch('/appointments/{id}/reject', [App\Http\Controllers\AppointmentController::class, 'reject'])->name('appointments.reject');
    Route::post('/appointments/{id}/send-email', [App\Http\Controllers\AppointmentController::class, 'sendEmail'])->name('appointments.send-email');
    Route::delete('/appointments/{id}/delete', [App\Http\Controllers\AppointmentController::class, 'delete'])->name('appointments.delete');
    Route::get('/showMedicalRecords' , [AppController::class, 'showMedicalRecords'])->name('showMedicalRecords');
    Route::get('/doctor/patient/{id}/record', [DoctorMedicalRecordController::class, 'show'])->name('doctor.record.show');
    Route::get('/doctor/patient/{id}/record/pdf', [DoctorMedicalRecordController::class, 'downloadPdf'])->name('doctor.record.pdf');
    Route::put('/doctor/patient/{id}/record', [DoctorMedicalRecordController::class, 'update'])->name('doctor.record.update');
    Route::post('/doctor/patient/{id}/visit', [DoctorVisitController::class, 'store'])->name('doctor.visit.store');
    Route::put('/doctor/visit/{visitId}', [DoctorVisitController::class, 'update'])->name('doctor.visit.update');
    Route::get('/payment', [AppController::class, 'showPaymentPage'])->name('payment');
    Route::get('/settings', [AppController::class, 'showSettingsPage'])->name('settings');
    Route::get('/doctor/notifications', [AppController::class, 'notifications'])->name('doctor.notifications');
    Route::post('/doctor/notifications/{id}/read', [AppController::class, 'markNotificationAsRead'])->name('doctor.notifications.read');
    Route::post('/doctor/notifications/read-all', [AppController::class, 'markAllNotificationsAsRead'])->name('doctor.notifications.read-all');
    Route::delete('/testimonials/{id}', [App\Http\Controllers\TestimonialController::class, 'delete'])->name('testimonials.delete');

    // Admin panel routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('doctors', AdminDoctorController::class);
        Route::resource('patients', PatientController::class);
        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::patch('appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::delete('appointments/{id}', [AdminAppointmentController::class, 'destroy'])->name('appointments.destroy');
        Route::get('medical-records', [AdminMedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::get('medical-records/{id}', [AdminMedicalRecordController::class, 'show'])->name('medical-records.show');
        Route::delete('medical-records/{id}', [AdminMedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    });
});

//ather routes
Route::get('/appsearch' , [AppController::class, 'appsearch'])->name('appsearch');
Route::post('/testimonials', [App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
