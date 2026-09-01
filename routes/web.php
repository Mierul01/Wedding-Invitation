<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RsvpController as AdminRsvpController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InvitationController::class, 'index'])->name('invitation.index');
Route::get('/calendar.ics', [InvitationController::class, 'calendar'])->name('invitation.calendar');
Route::post('/rsvp', [InvitationController::class, 'storeRsvp'])->name('rsvp.store');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/rsvps', [AdminRsvpController::class, 'index'])->name('rsvps.index');
        Route::get('/rsvps/{rsvp}/edit', [AdminRsvpController::class, 'edit'])->name('rsvps.edit');
        Route::put('/rsvps/{rsvp}', [AdminRsvpController::class, 'update'])->name('rsvps.update');
        Route::delete('/rsvps/{rsvp}', [AdminRsvpController::class, 'destroy'])->name('rsvps.destroy');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/toggle-rsvp', [SettingsController::class, 'toggleRsvp'])->name('settings.toggle-rsvp');
    });
});
