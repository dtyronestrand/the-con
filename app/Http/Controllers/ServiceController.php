<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Inertia\Inertia;
use App\Models\Category;

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
    if(!empty($validated['new_category'])) {
        $category = Category::firstOrCreate(['name' => $validated['new_category']]);
        $validated['category_id'] = $category->id;
    } elseif (empty($validated['category_id'])) {
        $defaultCategory = Category::firstOrCreate(['name' => "Default"]);
        $validated['category_id'] = $defaultCategory->id;
    }

  $localService = Service::create($validated);

  try {
    $token = \App\Models\AppSetting::where('key', 'api_token')->first()->value('value');
    $apiUrl = rtrim(config('app.api_url', 'http://localhost'), '/');

    $response = \Illuminate\Support\Facades\Http::withToken($token)
        ->post($apiUrl . '/api/services/sync', $request->all());

    if($response->successful()){
        $serverData = $response->json('service');
        $localService->update([
            'id' => $serverData['id']]);
    }
    } catch (\Exception $e) {
        // Log the error but don't fail the local creation
        \Illuminate\Support\Facades\Log::error('Failed to sync new service: ' . $e->getMessage());
  }
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
