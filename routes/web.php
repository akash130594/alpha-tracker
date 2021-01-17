<?php

use App\Http\Controllers\LanguageController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);


/*
 * Internal Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Web\Internal', 'as' => 'internal.'], function () {

    /*
     * Internal Controllers
     * All route names are prefixed with 'internal.'.
     */

    /*
     * These Internal controllers require the user to be logged in
     * All route names are prefixed with 'frontend.'
     * These routes can not be hit if the password is expired
     */
    Route::group(['middleware' => ['auth', 'password_expires']], function () {
        include_route_files(__DIR__ . '/internal/');
    });
});

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__.'/frontend/');
});







/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group([
    'namespace' => 'Backend',
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'permission:view backend'
], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/backend/');
});
