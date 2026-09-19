<?php

use App\Http\Controllers\Admin\DocumentationPageController as AdminDocumentationPageController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ThemeController as AdminThemeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationPageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/pages/{page}', [DocumentationPageController::class, 'show'])->name('pages.show');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'create'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('appearance', [AdminThemeController::class, 'edit'])->name('appearance.edit');
    Route::put('appearance', [AdminThemeController::class, 'update'])->name('appearance.update');

    Route::resource('projects', AdminProjectController::class)->except(['show']);

    Route::resource('projects.pages', AdminDocumentationPageController::class)
        ->except(['show'])
        ->scoped();
});
