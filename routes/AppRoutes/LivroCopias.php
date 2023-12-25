<?php

use App\Http\Controllers\LivroCopiasController;

Route::prefix('livros-copias')->group(function () {
    Route::controller(LivroCopiasController::class)->group(function () {

        Route::get('/', 'index')->name('livros-copias.index');
        Route::post('/', 'store')->name('livros-copias.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('livros-copias.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('livros-copias.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('livros-copias.destroy');
    });
});
