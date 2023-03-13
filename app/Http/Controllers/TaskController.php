<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return response()->json($tasks);
    }


    public function store(Request $request)
{
    // Verificar si el usuario tiene un token de autenticación
    if (!$request->header('Authorization')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Obtener el usuario autenticado
    $user = User::where('id', auth()->id())->first();

    // Validar los datos recibidos del formulario
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);

    // Crear la tarea asociada al usuario autenticado
    $task = new Task;
    $task->title = $validatedData['title'];
    $task->description = $validatedData['description'];
    $task->user_id = $user->id;
    $task->save();

    // Devolver la respuesta JSON con la tarea creada
    return response()->json($task);
}
public function update(Request $request, $id)
{
    // Verificar si el usuario tiene un token de autenticación
    if (!$request->header('Authorization')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Obtener la tarea a actualizar
    $task = Task::find($id);

    // Verificar si la tarea existe
    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }

    // Verificar si el usuario autenticado es el propietario de la tarea
    if ($task->user_id != auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Validar los datos recibidos del formulario
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);

    // Actualizar los datos de la tarea
    $task->title = $validatedData['title'];
    $task->description = $validatedData['description'];
    $task->save();

    // Devolver la respuesta JSON con la tarea actualizada
    return response()->json($task);
}

public function destroy(Request $request,$id)
{
    // Verificar si el usuario tiene un token de autenticación
    if (!$request->header('Authorization')) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Obtener la tarea a eliminar
    $task = Task::find($id);

    // Verificar si la tarea existe
    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }

    // Verificar si el usuario autenticado es el propietario de la tarea
    if ($task->user_id != auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Eliminar la tarea
    $task->delete();

    // Devolver la respuesta JSON con un mensaje de éxito
    return response()->json(['message' => 'Task deleted successfully']);
}


}
