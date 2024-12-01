<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerdictController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        // Redirect to the dashboard if the user is logged in
        return redirect()->route('dashboard');
    } else {
        // Redirect to the login page if the user is not logged in
        return redirect()->route('login');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('verdicts', VerdictController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/download-original/{uuid}', [FileController::class, 'downloadOriginal'])->name('download.original');
    Route::get('/download-stamped/{uuid}', [FileController::class, 'downloadStamped'])->name('download.stamped');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
