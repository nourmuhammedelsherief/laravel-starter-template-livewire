<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Tasks\TaskManager;
use App\Livewire\Tasks\TaskFormPage;
use App\Livewire\Employees\EmployeeIndex;
use App\Livewire\Employees\EmployeeForm;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('/tasks', TaskManager::class)->name('tasks.index');
    Route::get('/tasks/create', TaskFormPage::class)->name('tasks.create');
    Route::get('/tasks/{id}/edit', TaskFormPage::class)->name('tasks.edit');

    // Employees
    Route::get('/employees', EmployeeIndex::class)->name('employees.index');
    Route::get('/employees/create', EmployeeForm::class)->name('employees.create');
    Route::get('/employees/{id}/edit', EmployeeForm::class)->name('employees.edit');
});

require __DIR__.'/settings.php';
