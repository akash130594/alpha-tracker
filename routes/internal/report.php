<?php
use App\Http\Controllers\Web\Internal\Report\ReportController;

//Routes For Reports
Route::group([
    'namespace' => 'Report',
    'as' => 'report.',
    'middleware' => ['permission:access reports']
], function () {

    Route::get('report/index', [ReportController::class, 'index'])->name('index');
    Route::post('report/index', [ReportController::class, 'filterGetProjectBulk'])->name('filter.show');
    Route::post('report/export', [ReportController::class, 'bulkExportProject'])->name('export');
    Route::get('report/traffic-export/{id}/{category}', [ReportController::class, 'trafficExport'])->name('traffic.export');
    Route::get('report/screener-export/{id}/{category}', [ReportController::class, 'screenerExport'])->name('screener.export');


    /*Route::get('survey', 'SurveyController@index')->name('index');
    Route::get('survey/history', 'SurveyController@history')->name('history');*/
});
