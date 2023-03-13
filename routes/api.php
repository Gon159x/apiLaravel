<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\TaskController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/users',[ApiController::class,'users']);
Route::post('/login',[ApiController::class,'login']);
Route::post('/register', [ApiController::class, 'register']);

Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/tasks', 'App\Http\Controllers\TaskController@index');
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para actualizar una tarea
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    
    // Ruta para eliminar una tarea
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
  });
  


Route::get('/test', function () {
    return response()->json(['message' => 'working']);
});
