<?php
use App\Http\Controllers\Web\Internal\Setting\SettingController;

Route::group([
    'namespace' => 'Setting',
    'as' => 'setting.',
    'middleware' => ['permission:access setting']
], function () {
    Route::get('all-setting', [SettingController::class, 'index'])->name('index');
    Route::post('all-setting/post', [SettingController::class, 'postData'])->name('post.data');
    Route::get('all-setting/router-setting', [SettingController::class, 'routerSetting'])->name('router');
    Route::post('all-setting/router-setting/post', [SettingController::class, 'routerPostSetting'])->name('router.post');
});
