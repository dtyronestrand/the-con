<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Http;

class SyncController extends Controller
{
    public function sync()
    {
        // 1. Get the saved Token
        $tokenSetting = AppSetting::where('key', 'api_token')->first();

        if (! $tokenSetting) {
            return back()->withErrors(['msg' => 'No API token found. Please login first.']);
        }

        // 2. Call the Server
        $apiUrl = rtrim(env('API_URL'));
        $url = $apiUrl.'/api/services/pull';

        $response = Http::withToken($tokenSetting->value)
            ->timeout(10)
            ->get($url);

        if ($response->failed()) {
            return back()->withErrors(['msg' => 'Sync failed: '.$response->status()]);
        }

        // 3. Process the Data
        $categories = $response->json();

        $categoriesToUpsert = [];
        $servicesToUpsert = [];

        foreach ($categories as $catData) {
            $categoriesToUpsert[] = [
                'id' => $catData['id'],
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'name' => $catData['name'],
            ];

            if (isset($catData['services'])) {
                foreach ($catData['services'] as $serviceData) {
                    $servicesToUpsert[] = [
                        'id' => $serviceData['id'],
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'name' => $serviceData['name'],
                        'url' => $serviceData['url'],
                        'icon' => $serviceData['icon'],
                        'category_id' => $catData['id'],
                    ];
                }
            }
        }

        if (! empty($categoriesToUpsert)) {
            foreach (array_chunk($categoriesToUpsert, 1000) as $chunk) {
                Category::upsert(
                    $chunk,
                    ['id'], // Match by ID
                    ['name'] // Update name
                );
            }
        }

        if (! empty($servicesToUpsert)) {
            foreach (array_chunk($servicesToUpsert, 1000) as $chunk) {
                Service::upsert(
                    $chunk,
                    ['id'], // Match by ID
                    ['name', 'url', 'icon', 'category_id'] // Update these columns
                );
            }
        }

        return back()->with('status', 'Services synced successfully!');
    }
}
