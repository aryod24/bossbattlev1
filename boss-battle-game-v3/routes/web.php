<?php

use App\Http\Controllers\Admin\SoloRaidAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoloRaidController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Get latest badge
    $latestBadge = $user->userBadges()->with('badge')->latest()->first();
    
    // Get last raid session
    $lastRaidSession = $user->sessionSolos()->with('soloRaid')->latest()->first();
    
    // Get active event (prioritize ongoing, then upcoming)
    $activeEvent = \App\Models\Event::where('status', 'ongoing')->latest()->first();
    if (!$activeEvent) {
         $activeEvent = \App\Models\Event::where('status', 'draft')->latest()->first(); // Or upcoming logic
    }

    return view('dashboard', compact('latestBadge', 'lastRaidSession', 'activeEvent'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Player Routes
    Route::get('/solo', [SoloRaidController::class, 'index'])->name('solo.index');
    Route::get('/solo/{soloRaid}/map', [SoloRaidController::class, 'map'])->name('solo.map');
    Route::get('/solo/{soloRaid}/materi/{nodeId}', [SoloRaidController::class, 'materi'])->name('solo.materi');
    Route::get('/solo/{soloRaid}/level-select', [SoloRaidController::class, 'levelSelect'])->name('solo.level-select');
    
    // Battle Routes
    Route::get('/solo/{soloRaid}/battle/init/{level}', [\App\Http\Controllers\SoloBattleController::class, 'init'])->name('solo.battle.init');
    Route::get('/solo/{soloRaid}/battle/{session?}', [\App\Http\Controllers\SoloBattleController::class, 'index'])->name('solo.battle');
    Route::post('/solo/{soloRaid}/battle/action', [\App\Http\Controllers\SoloBattleController::class, 'action'])->name('solo.battle.action');
    Route::post('/solo/{soloRaid}/battle/finish/{session}', [\App\Http\Controllers\SoloBattleController::class, 'finish'])->name('solo.battle.finish');
    Route::get('/solo/result/{session}', [\App\Http\Controllers\SoloBattleController::class, 'result'])->name('solo.result');
    Route::get('/solo/{soloRaid}/battle/question', [\App\Http\Controllers\SoloBattleController::class, 'getQuestion'])->name('solo.battle.question');
    Route::post('/solo/check-expired', [\App\Http\Controllers\SoloBattleController::class, 'checkExpired'])->name('solo.check-expired');
});

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('solo-raids', SoloRaidAdminController::class);
    Route::post('solo-raids/{soloRaid}/duplicate', [SoloRaidAdminController::class, 'duplicate'])->name('solo-raids.duplicate');
    Route::post('solo-raids/{soloRaid}/toggle-level', [SoloRaidAdminController::class, 'toggleLevel'])->name('solo-raids.toggle-level');
    
    Route::get('questions/template', [\App\Http\Controllers\Admin\QuestionBankController::class, 'downloadTemplate'])->name('questions.template');
    Route::post('questions/bulk-upload', [\App\Http\Controllers\Admin\QuestionBankController::class, 'bulkUpload'])->name('questions.bulk-upload');
    Route::resource('questions', \App\Http\Controllers\Admin\QuestionBankController::class);

    // Session Monitor
    Route::get('sessions', [\App\Http\Controllers\Admin\AdminSessionController::class, 'index'])->name('sessions.index');
    Route::get('sessions/{id}', [\App\Http\Controllers\Admin\AdminSessionController::class, 'show'])->name('sessions.show');
    Route::post('sessions/destroy', [\App\Http\Controllers\Admin\AdminSessionController::class, 'destroy'])->name('sessions.destroy');
    Route::post('sessions/clear', [\App\Http\Controllers\Admin\AdminSessionController::class, 'clear'])->name('sessions.clear');
    Route::post('sessions/check-expired', [\App\Http\Controllers\Admin\AdminSessionController::class, 'checkExpired'])->name('sessions.check-expired');
    
    // Image Upload for Materi
    Route::post('upload-materi-image', [\App\Http\Controllers\Admin\ImageUploadController::class, 'uploadMateriImage'])->name('upload.materi-image');

    // Badges Management
    Route::resource('badges', \App\Http\Controllers\Admin\BadgeController::class);

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

require __DIR__.'/auth.php';
