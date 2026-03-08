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
        

        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'done' => 'boolean',
            'due_date' => 'nullable|date',
            'sub_tasks' => 'nullable|array',
            'attachments' => 'nullable|array',
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'notes' => $request->notes ?? null,
            'done' => $request->done ?? false,
            'due_date' => $request->due_date ?? null,
            'sub_tasks' => $request->sub_tasks ?? [],
            'attachments' => $request->attachments ?? [],
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