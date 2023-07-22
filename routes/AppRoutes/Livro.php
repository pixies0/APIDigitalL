<?php

use App\Http\Controllers\LivroController;

Route::prefix('livros')->group(function () {
    Route::controller(LivroController::class)->group(function () {

        Route::get('/', 'index')->name('livros.index');
        Route::post('/', 'store')->name('livros.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('livros.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('livros.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('livros.destroy');
    });
});
