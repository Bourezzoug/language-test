<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestCrudController;
use App\Http\Controllers\SectionCrudController;
use App\Http\Controllers\QuestionCrudController;
use App\Http\Controllers\StudentTestController;

/*
|--------------------------------------------------------------------------
| Student Portal Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StudentTestController::class, 'index'])->name('student.index');
Route::get('/test/{test}/start', [StudentTestController::class, 'start'])->name('student.test.start');
Route::post('/test/{test}/initialize', [StudentTestController::class, 'initialize'])->name('student.test.initialize');
Route::get('/attempt/{attempt}/section/{section}', [StudentTestController::class, 'showSection'])->name('student.test.section');
Route::post('/attempt/{attempt}/section/{section}/submit', [StudentTestController::class, 'submitSection'])->name('student.test.submit');
Route::get('/attempt/{attempt}/result', [StudentTestController::class, 'showResult'])->name('student.test.result');
Route::post('/attempt/{attempt}/start-timer', [StudentTestController::class, 'startListeningTimer'])->name('student.test.start-timer');
Route::post('/attempt/{attempt}/audio-play', [StudentTestController::class, 'incrementAudioPlayCount'])->name('student.test.audio-play');
Route::post('/attempt/{attempt}/save-answer', [StudentTestController::class, 'saveSingleAnswer'])->name('student.test.save-answer');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Control Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Overview
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Candidates / Attempts History
    Route::get('/attempts', [AdminController::class, 'attempts'])->name('attempts.index');
    Route::get('/attempts/export', [AdminController::class, 'exportAttempts'])->name('attempts.export');
    Route::get('/attempts/{attempt}', [AdminController::class, 'showAttempt'])->name('attempts.show');

    // Tests CRUD
    Route::get('/tests', [TestCrudController::class, 'index'])->name('tests.index');
    Route::get('/tests/create', [TestCrudController::class, 'create'])->name('tests.create');
    Route::post('/tests', [TestCrudController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestCrudController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestCrudController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestCrudController::class, 'destroy'])->name('tests.destroy');
    Route::post('/tests/{test}/toggle-active', [TestCrudController::class, 'toggleActive'])->name('tests.toggle-active');

    // Test Sections CRUD
    Route::get('/tests/{test}/sections', [SectionCrudController::class, 'index'])->name('tests.sections.index');
    Route::get('/tests/{test}/sections/create', [SectionCrudController::class, 'create'])->name('tests.sections.create');
    Route::post('/tests/{test}/sections', [SectionCrudController::class, 'store'])->name('tests.sections.store');
    Route::get('/sections/{section}/edit', [SectionCrudController::class, 'edit'])->name('sections.edit');
    Route::put('/sections/{section}', [SectionCrudController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionCrudController::class, 'destroy'])->name('sections.destroy');

    // Questions & Reading Passages CRUD inside Sections
    Route::get('/sections/{section}/questions', [QuestionCrudController::class, 'index'])->name('sections.questions.index');
    
    // Reading Passages
    Route::get('/sections/{section}/passages/create', [QuestionCrudController::class, 'createPassage'])->name('sections.passages.create');
    Route::post('/sections/{section}/passages', [QuestionCrudController::class, 'storePassage'])->name('sections.passages.store');
    Route::get('/passages/{passage}/edit', [QuestionCrudController::class, 'editPassage'])->name('passages.edit');
    Route::put('/passages/{passage}', [QuestionCrudController::class, 'updatePassage'])->name('passages.update');
    Route::delete('/passages/{passage}', [QuestionCrudController::class, 'destroyPassage'])->name('passages.destroy');

    // Section Questions
    Route::get('/sections/{section}/questions/create', [QuestionCrudController::class, 'createQuestion'])->name('sections.questions.create');
    Route::post('/sections/{section}/questions', [QuestionCrudController::class, 'storeQuestion'])->name('sections.questions.store');
    Route::get('/questions/{question}/edit', [QuestionCrudController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/questions/{question}', [QuestionCrudController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionCrudController::class, 'destroyQuestion'])->name('questions.destroy');

});
