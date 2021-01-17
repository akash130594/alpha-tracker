<?php
use App\Http\Controllers\Web\Internal\User\UserController;

Route::group([
    'namespace' => 'User',
    'as' => 'user.'
], function () {

    Route::get('profile', [UserController::class, 'getProfileDetails'])->name('profile');
    Route::get('profile_update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile_update/{id}', [UserController::class, 'postUpdateProfile'])->name('profile.update.post');
    Route::get('password_change/{id}', [UserController::class, 'changePassword'])->name('profile.change.password');
    Route::post('password_change/{id}', [UserController::class, 'editPassword'])->name('profile.edit.password');
    Route::get('setting/', [UserController::class, 'setting'])->name('profile.setting');
});
