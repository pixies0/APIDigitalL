<?php

use App\Http\Controllers\LivroAutorController;

Route::prefix('livros-autor')->group(function () {
    Route::controller(LivroAutorController::class)->group(function () {

        Route::get('/', 'index')->name('livros-autor.index');
        Route::post('/', 'store')->name('livros-autor.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('livros-autor.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('livros-autor.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('livros-autor.destroy');
    });
});
