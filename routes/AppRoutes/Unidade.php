<?php

use App\Http\Controllers\UnidadeController;

Route::prefix('unidades')->group(function () {
    Route::controller(UnidadeController::class)->group(function () {

        Route::get('/', 'index')->name('unidade.index');
        Route::post('/', 'store')->name('unidade.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('unidade.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('unidade.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('unidade.destroy');
    });
});
