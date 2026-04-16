<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Tasks\TaskManager;
use App\Livewire\Tasks\TaskFormPage;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('/tasks', TaskManager::class)->name('tasks.index');
    Route::get('/tasks/create', TaskFormPage::class)->name('tasks.create');
    Route::get('/tasks/{id}/edit', TaskFormPage::class)->name('tasks.edit');
});

require __DIR__.'/settings.php';
