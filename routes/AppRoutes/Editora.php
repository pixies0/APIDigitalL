<?php

use App\Http\Controllers\EditoraController;

Route::prefix('editoras')->group(function () {
    Route::controller(EditoraController::class)->group(function () {

        Route::get('/', 'index')->name('editora.index');
        Route::post('/', 'store')->name('editora.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('editora.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('editora.update');
    });
});
