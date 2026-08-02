<?php

use App\Http\Controllers\DocumentationPageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/pages/{page}', [DocumentationPageController::class, 'show'])->name('pages.show');
