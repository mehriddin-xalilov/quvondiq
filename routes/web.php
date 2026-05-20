<?php

use App\Http\Controllers\CertificateVerifyController;
use App\Http\Controllers\SertifikatVerifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\GuvohnomaController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\ProfessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// --- Ochiq sahifalar (login talab etilmaydi) ---
// Guvohnoma verify
Route::get('/verify/{code}', [CertificateVerifyController::class, 'show'])
    ->name('certificate.verify');
Route::get('/verify/{code}/download', [CertificateVerifyController::class, 'downloadPdf'])
    ->name('certificate.download');

// Sertifikat verify
Route::get('/sertifikat/{code}', [SertifikatVerifyController::class, 'show'])
    ->name('sertifikat.verify');
Route::get('/sertifikat/{code}/download', [SertifikatVerifyController::class, 'downloadPdf'])
    ->name('sertifikat.download');


Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Templates (admin) — order: static URI's must come before {param} matches
    Route::get('templates/create', [DocumentTemplateController::class, 'create'])
        ->middleware('permission:templates.create')->name('templates.create');
    Route::post('templates', [DocumentTemplateController::class, 'store'])
        ->middleware('permission:templates.create')->name('templates.store');

    Route::get('templates/{template}/download', [DocumentTemplateController::class, 'download'])
        ->middleware('permission:templates.view')->name('templates.download');
    Route::get('templates/{template}/edit', [DocumentTemplateController::class, 'edit'])
        ->middleware('permission:templates.edit')->name('templates.edit');

    Route::get('templates', [DocumentTemplateController::class, 'index'])
        ->middleware('permission:templates.view')->name('templates.index');
    Route::get('templates/{template}', [DocumentTemplateController::class, 'show'])
        ->middleware('permission:templates.view')->name('templates.show');

    Route::match(['put', 'patch'], 'templates/{template}', [DocumentTemplateController::class, 'update'])
        ->middleware('permission:templates.edit')->name('templates.update');
    Route::delete('templates/{template}', [DocumentTemplateController::class, 'destroy'])
        ->middleware('permission:templates.delete')->name('templates.destroy');

    // Sertifikatlar / Guvohnomalar
    Route::middleware('permission:documents.generate')->group(function () {
        Route::get('sertifikatlar/samples', [SertifikatController::class, 'samples'])
            ->name('sertifikatlar.samples');
        Route::get('sertifikatlar/{sertifikat}/sample-data', [SertifikatController::class, 'sampleData'])
            ->name('sertifikatlar.sample-data');
        Route::get('sertifikatlar/{sertifikat}/download', [SertifikatController::class, 'download'])
            ->name('sertifikatlar.download');
        Route::resource('sertifikatlar', SertifikatController::class)
            ->parameters(['sertifikatlar' => 'sertifikat']);

        Route::get('guvohnomalar/samples', [GuvohnomaController::class, 'samples'])
            ->name('guvohnomalar.samples');
        Route::get('guvohnomalar/{guvohnoma}/sample-data', [GuvohnomaController::class, 'sampleData'])
            ->name('guvohnomalar.sample-data');
        Route::get('guvohnomalar/{guvohnoma}/download', [GuvohnomaController::class, 'download'])
            ->name('guvohnomalar.download');
        Route::resource('guvohnomalar', GuvohnomaController::class)
            ->parameters(['guvohnomalar' => 'guvohnoma']);
    });

    // Lookup AJAX
    Route::get('lookups/districts', [LookupController::class, 'districts'])->name('lookups.districts');

    // Professions (Mutaxassisliklar)
    Route::middleware('permission:lookups.manage')->group(function () {
        Route::resource('professions', ProfessionController::class)->except(['show']);
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
        Route::resource('roles', RoleController::class)->middleware('permission:roles.view');

        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class)->middleware('permission:users.view');
    });
});

require __DIR__.'/auth.php';