<?php
use App\Http\Controllers\Web\Internal\DashboardController;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
