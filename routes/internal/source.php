<?php
use App\Http\Controllers\Web\Internal\Source\SourceController;

//Routes For Sources
Route::group([
    'namespace' => 'Source',
    'as' => 'source.',
    'middleware' => ['permission:access sources']
], function () {
    Route::get('source', [SourceController::class, 'index'])->name('index');
    Route::get('source/datatables', [SourceController::class, 'datatable'])->name('datatable');
    Route::get('source/create', [SourceController::class, 'createSource'])->name('create.show');
    Route::post('source/create', [SourceController::class, 'postCreateSource'])->name('create.source');
    Route::get('source/link', [SourceController::class, 'showLink'])->name('link.show');
    Route::get('source/edit/{id}', [SourceController::class, 'editSource'])->name('edit.show');
    Route::get('source/delete/{id}', [SourceController::class, 'deleteSource'])
        ->name('delete.show')->middleware(['permission:delete sources']);
    Route::patch('source/edit/{id}', [SourceController::class, 'updateSource'])->name('update');
});
