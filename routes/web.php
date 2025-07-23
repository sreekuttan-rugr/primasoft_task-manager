<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Task Management Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/tasks/{task}/update-fake', [TaskController::class, 'update'])->name('tasks.update-fake');

    
    Route::resource('tasks', TaskController::class);

    // Then your custom routes
    Route::get('/tasks/status/{status}', [TaskController::class, 'tasksByStatus'])
        ->name('tasks.by-status');

        

    Route::patch('/tasks/{task}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggle-status');
    Route::post('/tasks/bulk-update', [TaskController::class, 'bulkUpdate'])->name('tasks.bulk-update');

    // Category routes
    Route::resource('categories', CategoryController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);

    Route::middleware(['admin'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Import routes (admin only)
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::post('/import/csv', [ImportController::class, 'importCsv'])->name('import.csv');
        Route::get('/import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
        Route::get('/import/history', [ImportController::class, 'history'])->name('import.history');
    });
});


require __DIR__.'/auth.php';