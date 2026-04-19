<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StickyNote;
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
        $stickyNotes = StickyNote::where('user_id', Auth::id())->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'stickyNotes' => $stickyNotes,
            'isGoogleConnected' => session()->has('google_token'),
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
