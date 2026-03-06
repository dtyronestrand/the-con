<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;
use App\Services\SyncService;

class CategoryController extends Controller
{
    public function index(SyncService $syncer)
    {
        $syncer->sync();
        $categories = Category::with('services')->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'isConnected' => session()->has('outlook_token'),
            'events' => Inertia::lazy(fn() => $this->fetchOutlookEvents()),
        ]);
    }

    protected function fetchOutlookEvents()
    {
        $isConnected = session()->has('outlook_token');
        
        if (!$isConnected) {
            return [];
        }

        $token = session('outlook_token');
        $startDateTime = \Carbon\Carbon::now()->toIso8601String();
        $endDateTime = \Carbon\Carbon::now()->addDays(3)->toIso8601String();

        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->get('https://graph.microsoft.com/v1.0/me/calendarview', [
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                '$select' => 'subject,organizer,start,end',
                '$orderby' => 'start/dateTime',
            ]);

        if ($response->successful()) {
            return $response->json()['value'];
        }

        return [];
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
