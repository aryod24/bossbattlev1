<?php

use App\Http\Controllers\Admin\SoloRaidAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoloRaidController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Player Routes
    Route::get('/solo', [SoloRaidController::class, 'index'])->name('solo.index');
    Route::get('/solo/{soloRaid}/map', [SoloRaidController::class, 'map'])->name('solo.map');
    Route::get('/solo/{soloRaid}/info/{nodeId}', [SoloRaidController::class, 'info'])->name('solo.info');
    Route::get('/solo/{soloRaid}/level-select', [SoloRaidController::class, 'levelSelect'])->name('solo.level-select');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('solo-raids', SoloRaidAdminController::class);
    Route::post('solo-raids/{soloRaid}/duplicate', [SoloRaidAdminController::class, 'duplicate'])->name('solo-raids.duplicate');
    Route::post('solo-raids/{soloRaid}/toggle-level', [SoloRaidAdminController::class, 'toggleLevel'])->name('solo-raids.toggle-level');
    
    Route::get('questions/template', [\App\Http\Controllers\Admin\QuestionBankController::class, 'downloadTemplate'])->name('questions.template');
    Route::post('questions/bulk-upload', [\App\Http\Controllers\Admin\QuestionBankController::class, 'bulkUpload'])->name('questions.bulk-upload');
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionBankController::class);
});

require __DIR__.'/auth.php';
