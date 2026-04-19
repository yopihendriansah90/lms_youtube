<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MemberPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('member.home') : redirect()->route('login'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/home', [MemberPortalController::class, 'dashboard'])->name('member.home');
    Route::get('/materi', [MemberPortalController::class, 'materials'])->name('member.materials');
    Route::get('/materi/{material:slug}', [MemberPortalController::class, 'showMaterial'])->name('member.materials.show');
    Route::get('/materi/{material:slug}/dokumen/{document}', [MemberPortalController::class, 'showPdfDocument'])->name('member.materials.documents.show');
    Route::get('/room-zoom', [MemberPortalController::class, 'rooms'])->name('member.rooms');
    Route::get('/rekaman-zoom', [MemberPortalController::class, 'zoomRecords'])->name('member.zoom');
    Route::get('/mentor', [MemberPortalController::class, 'mentors'])->name('member.mentors');
    Route::get('/tanya-jawab', [MemberPortalController::class, 'questions'])->name('member.questions');
    Route::post('/tanya-jawab', [MemberPortalController::class, 'storeQuestion'])->name('member.questions.store');
});
