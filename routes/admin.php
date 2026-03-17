<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeLeaveQuotaController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\LeaveRequestController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PdfController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/social', [SettingController::class, 'updateSocial'])->name('settings.social.update');
    Route::put('/settings/smtp', [SettingController::class, 'updateSmtp'])->name('settings.smtp.update');

    Route::post('/status/update', StatusController::class)->name('status.update');
    Route::post('/upload', UploadController::class)->name('upload');

    Route::resource('pages', PageController::class)->except('show');
    Route::resource('departments', DepartmentController::class)->except('show');
    Route::resource('employees', EmployeeController::class);
    Route::get('employees-offboarded', [EmployeeController::class, 'offboarded'])->name('employees.offboarded');
    Route::put('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
    Route::get('employees/{employee}/leave-quotas', [EmployeeLeaveQuotaController::class, 'edit'])->name('employees.leave-quotas.edit');
    Route::put('employees/{employee}/leave-quotas', [EmployeeLeaveQuotaController::class, 'update'])->name('employees.leave-quotas.update');
    Route::resource('holidays', HolidayController::class)->except('show');
    Route::resource('leave-types', LeaveTypeController::class)->except('show');
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::put('leave-requests/{leave_request}/update-status', [LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');
    Route::post('leave-requests/get-leave-balance', [LeaveRequestController::class, 'getLeaveBalance'])->name('leave-requests.get-leave-balance');
    Route::get('pdf-generate/{pdf}', [PdfController::class, 'pdf_generate'])->name('pdf.generate');

    Route::middleware('can:admin')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});
