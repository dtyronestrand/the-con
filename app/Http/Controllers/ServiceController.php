<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
public function index()
{
    $services = Service::all();
    return Inertia::render('Welcome', [
        'services' => $services,
    ]);
}
 public function store(Request $request)
 {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'url' => 'nullable|url|max:255',
        'icon' => 'nullable|string|max:255',
    ]);

    $service = Service::create($validated);

    return back()->with('success', 'Service created successfully.');
 }
public function update(Request $request, $id)
 {
    $service = Service::findOrFail($id);

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'url' => 'sometimes|nullable|url|max:255',
        'icon' => 'sometimes|nullable|string|max:255',
    ]);

    $service->update($validated);

    return response()->json($service, 200);
 }

 public function destroy($id)
 {
    $service = Service::findOrFail($id);
    $service->delete();

    return response()->json(null, 204);
 }
}
