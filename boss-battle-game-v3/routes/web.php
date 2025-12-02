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
    
    // Battle Routes
    Route::get('/solo/{soloRaid}/battle/init/{level}', [\App\Http\Controllers\SoloBattleController::class, 'init'])->name('solo.battle.init');
    Route::get('/solo/{soloRaid}/battle', [\App\Http\Controllers\SoloBattleController::class, 'index'])->name('solo.battle');
    Route::post('/solo/{soloRaid}/battle/action', [\App\Http\Controllers\SoloBattleController::class, 'action'])->name('solo.battle.action');
    Route::get('/solo/{soloRaid}/battle/question', [\App\Http\Controllers\SoloBattleController::class, 'getQuestion'])->name('solo.battle.question');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('solo-raids', SoloRaidAdminController::class);
    Route::post('solo-raids/{soloRaid}/duplicate', [SoloRaidAdminController::class, 'duplicate'])->name('solo-raids.duplicate');
    Route::post('solo-raids/{soloRaid}/toggle-level', [SoloRaidAdminController::class, 'toggleLevel'])->name('solo-raids.toggle-level');
    
    Route::get('questions/template', [\App\Http\Controllers\Admin\QuestionBankController::class, 'downloadTemplate'])->name('questions.template');
    Route::post('questions/bulk-upload', [\App\Http\Controllers\Admin\QuestionBankController::class, 'bulkUpload'])->name('questions.bulk-upload');
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionBankController::class);
});

require __DIR__.'/auth.php';
