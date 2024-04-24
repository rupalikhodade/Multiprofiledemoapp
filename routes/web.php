<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
    Route::get('/personal', [ProfileController::class, 'personal_profile_edit'])->name('profile.personal_profile_edit');
    Route::patch('/personal', [ProfileController::class, 'personal_profile_update'])->name('profile.personal_profile_update');
    Route::get('/professional', [ProfileController::class, 'professional_profile_edit'])->name('profile.professional_profile_edit');
    Route::patch('/professional', [ProfileController::class, 'professional_profile_update'])->name('profile.professional_profile_update');
});

require __DIR__.'/auth.php';
