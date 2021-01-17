<?php
use App\Http\Controllers\Web\Internal\Archive\ArchiveController;

//Routes For Archives Controller
Route::group([
    'namespace' => 'Archive',
    'as' => 'archive.',
    'middleware' => ['permission:access surveys|access archives']
], function () {
    Route::get('archives/all', [ArchiveController::class, 'index'])->name('user.index');
    Route::get('archives/summary/{id}', [ArchiveController::class, 'summary'])->name('view.summary');
    Route::get('archives/view-details/{id}', [ArchiveController::class, 'viewDetails'])->name('view.details');
    Route::get('archives/clone/{id}', [ArchiveController::class, 'reBuildProject'])->name('clone.archive');
    Route::post('archives/all', [ArchiveController::class, 'filterGetArchive'])->name('filter.show');
    Route::get('quick/export/{id}', [ArchiveController::class, 'quickExport'])->name('quick.export');
});
