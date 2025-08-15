<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [new Middleware('auth:sanctum')];
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        return $request->user()->tasks();
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        return $request->user()->tasks()->create($fields);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        return $task;
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $fields = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
        ]);

        $task->update($fields);

        return $task;
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
