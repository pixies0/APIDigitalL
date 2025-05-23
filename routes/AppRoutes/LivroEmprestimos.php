<?php

use App\Http\Controllers\LivroEmprestimosController;

Route::prefix('livros-emprestimos')->group(function () {
    Route::controller(LivroEmprestimosController::class)->group(function () {
        Route::get('/', 'index')->name('livros-emprestimos.index');
        Route::post('/', 'store')->name('livros-emprestimos.store');
        Route::get('/{id}', 'show')->whereNumber('id')->name('livros-emprestimos.show');
        Route::put('/{id}', 'update')->whereNumber('id')->name('livros-emprestimos.update');
        Route::delete('/{id}', 'destroy')->whereNumber('id')->name('livros-emprestimos.destroy');
    });
});
