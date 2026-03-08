<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'done' => 'boolean',
            'due_date' => 'nullable|date',
            'sub_tasks' => 'nullable|array',
            'attachments' => 'nullable|array',
        ]);

        $task = new Task([
            'name' => $validatedData['name'],
            'user_id' => $validatedData['user_id'],
            'notes' => $validatedData['notes'] ?? null,
            'done' => $validatedData['done'] ?? false,
            'due_date' => $validatedData['due_date'] ?? null,
            'sub_tasks' => $validatedData['sub_tasks'] ?? [],
            'attachments' => $validatedData['attachments'] ?? [],
        ]);

        $task->save();


        return back()->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'done' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'sub_tasks' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'name' => $validatedData['name'],
            'user_id' => $validatedData['user_id'],
            'notes' => $validatedData['notes'] ?? null,
            'done' => $validatedData['done'] ?? false,
            'due_date' => $validatedData['due_date'] ?? null,
            'sub_tasks' => isset($validatedData['sub_tasks']) ? json_decode($validatedData['sub_tasks'], true) : [],
            'attachments' => $validatedData['attachments'] ?? [],
        ]);

        return back()->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}