<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PedidoController;

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

// Rotas públicas de autenticação
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:api')->group(function () {
    // Autenticação
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // Pedidos
    Route::get('pedidos', [PedidoController::class, 'index']);
    Route::post('pedidos', [PedidoController::class, 'store']);
    Route::get('pedidos/{id}', [PedidoController::class, 'show']);
    Route::patch('pedidos/{id}/status', [PedidoController::class, 'updateStatus']);
    Route::get('pedidos/status/options', [PedidoController::class, 'getStatusOptions']);
});
