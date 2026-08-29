<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\SyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(SyncService $syncer)
    {
        $syncer->sync();
        $categories = Category::with('services')->get();
        $notes = Auth::user()?->notes()->with('tasks')->orderByDesc('created_at')->get() ?? collect();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'notes' => $notes,
            'needsReconnect' => $syncer->needsReconnect(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
