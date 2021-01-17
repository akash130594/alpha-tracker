<?php
use App\Http\Controllers\Web\Internal\Client\ClientController;
use App\Http\Controllers\Web\Internal\Client\ClientSecurity\ClientSecurityController;

/*
* Clients Routes
* Namespaces indicate folder structure
*/
Route::group([
    'namespace' => 'Client',
    'as' => 'client.',
    'middleware' => ['permission:access clients']
], function () {
    Route::get('client', [ClientController::class, 'index'])->name('index');
    Route::get('client/datatables', [ClientController::class, 'datatable'])->name('datatable');
    Route::get('client/create', [ClientController::class, 'createClient'])->name('create.show');
    Route::post('client/create', [ClientController::class, 'postCreateClient'])->name('create.client');

    Route::get('client/edit/{id}', [ClientController::class, 'editClient'])->name('edit.show');
    Route::get('client/security', [ClientSecurityController::class, 'index'])->name('security.show');
    Route::get('client-security/datatables', [ClientSecurityController::class, 'datatable'])->name('security.datatable');
    Route::get('client-security/edit/{id}', [ClientSecurityController::class, 'editClientSecurity'])->name('security.edit');
    Route::post('client-security/edit-post/{id}', [ClientSecurityController::class, 'postEditClientSecurity'])->name('security.edit.post');
    Route::get('client-security/create', [ClientSecurityController::class, 'createSecurityType'])->name('security.create.show');
    Route::post('client-security/create/post', [ClientSecurityController::class, 'postCreateSecurityType'])->name('security.create.post');
    Route::patch('client/edit/{id}', [ClientController::class, 'updateClient'])->name('update');

    Route::get('client/delete/{id}', [ClientController::class, 'deleteClient'])
        ->name('delete.show')->middleware(['permission:delete clients']);


    Route::group([
        'middleware' => ['permission:access client security']
    ], function () {
        Route::get('client/edit/security/{id}', [ClientController::class, 'editSecurity'])->name('edit.security.show');
        Route::get('client/edit/{id}/security-data/fetch', [ClientController::class, 'getSecurityTypeForm'])->name('edit.security.data.show');
        Route::patch('client/edit/{id}/security-data/update', [ClientController::class, 'updateClientSecurityImpl'])->name('edit.security.update');
    });

});
