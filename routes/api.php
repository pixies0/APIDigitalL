<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Orangehill\Iseed\IseedServiceProvider::class;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json("Saúde");
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('jwt.verify')->group(function () {
    // Rotas de Autenticação
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Rotas de CRUD do Sistema
    include "AppRoutes/Editora.php";
    include "AppRoutes/Livro.php";
    include "AppRoutes/Unidade.php";
    include "AppRoutes/LivroAutor.php";
    include "AppRoutes/LivroCopias.php";
});