<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\TarefaExportController;
use App\Http\Controllers\TarefaImportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    Route::apiResource('tarefas', TarefaController::class);
    // Route::middleware('auth:api')->get('/tarefas/exportar', [TarefaExportController::class, 'export']);

    
    Route::get('/tarefas/filtrar/status/{status}', [TarefaController::class, 'filtrarPorStatus']);
    Route::get('/tarefas/filtrar/prioridade/{prioridade}', [TarefaController::class, 'filtrarPorPrioridade']);
    Route::post('/tarefas/importar', [TarefaImportController::class, 'import'])->name('tarefas.import');

});
