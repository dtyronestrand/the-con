<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Service;

class SyncController extends Controller
{
    public function sync()
    {
        // 1. Get the saved Token
        $tokenSetting = AppSetting::where('key', 'api_token')->first();

        if (!$tokenSetting) {
            return back()->withErrors(['msg' => 'No API token found. Please login first.']);
        }

        // 2. Call the Server (Use the Android IP 10.0.2.2 if testing on Emulator)
        // In production, use your real domain.
        $url = 'http://10.0.2.2:8000/api/sync/services'; 

        $response = Http::withToken($tokenSetting->value)
                        ->timeout(10)
                        ->get($url);

        if ($response->failed()) {
            return back()->withErrors(['msg' => 'Sync failed: ' . $response->status()]);
        }

        // 3. Process the Data
        $categories = $response->json();

        foreach ($categories as $catData) {
            // Save the Category
            Category::updateOrCreate(
                ['id' => $catData['id']], // Match by ID
                ['name' => $catData['name']]
            );

            // Save the Services belonging to this Category
            if (isset($catData['services'])) {
                foreach ($catData['services'] as $serviceData) {
                    Service::updateOrCreate(
                        ['id' => $serviceData['id']], // Match by ID
                        [
                            'name' => $serviceData['name'],
                            'url'  => $serviceData['url'],
                            'icon' => $serviceData['icon'],
                            'category_id' => $catData['id'], // Ensure connection
                        ]
                    );
                }
            }
        }

        return back()->with('status', 'Services synced successfully!');
    }
}