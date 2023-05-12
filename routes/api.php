<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

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

Route::get('/UsuarioList', [UsuarioController::class, 'index']);
Route::post('/UsuarioAdd', [UsuarioController::class, 'store']);
Route::get('/BuscarUsuario', [UsuarioController::class, 'buscarPorUsuario']);
Route::put('/ActualizarUsuario/{usuario}', [UsuarioController::class, 'actualizarUsuario']);
Route::delete('EliminarUsuarios/{usuario}', [UsuarioController::class, 'destroy']);


