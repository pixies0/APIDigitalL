<?php

use App\Http\Controllers\EditoraController;

Route::prefix('editoras')->group(function () {
    Route::controller(EditoraController::class)->group(function () {

        Route::get('/', 'index')->name('editora.index');
    });
});
