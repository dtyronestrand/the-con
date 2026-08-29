<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'new_category' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'icon' => 'nullable|string|max:255',
        ]);
        if (! empty($validated['new_category'])) {
            $category = Category::firstOrCreate(['name' => $validated['new_category']]);
            $validated['category_id'] = $category->id;
        } elseif (empty($validated['category_id'])) {
            $defaultCategory = Category::firstOrCreate(['name' => 'Default']);
            $validated['category_id'] = $defaultCategory->id;
        }

        // SyncObserver (registered on the Service model) queues this for push
        // to the remote server — no need to push it here too.
        Service::create($validated);

        return redirect()->back()->with('success', 'Service created successfully.');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|nullable|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|nullable|url|max:255',
            'icon' => 'sometimes|nullable|string|max:255',
        ]);

        $service->update($validated);

        return redirect()->back()->with('success', 'Service updated successfully.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}
