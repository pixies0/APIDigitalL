<?php

use App\Http\Controllers\EditoraController;
use Illuminate\Http\Request;
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


include "AppRoutes/Editora.php";
include "AppRoutes/Livro.php";
include "AppRoutes/Unidade.php";
include "AppRoutes/LivroAutor.php";
include "AppRoutes/LivroCopias.php";
