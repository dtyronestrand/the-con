<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Service;

class SyncService
{
    public function run()
    {
        // 1. Check if we have a token
        $tokenSetting = AppSetting::where('key', 'api_token')->first();
        if (!$tokenSetting) {
            Log::info('Sync skipped: No API token found');
            return;
        }

        // 2. Optimization: Don't sync if we just synced less than 1 minute ago
        // (Prevents slowing down the app on every single refresh)
        $lastSync = session('last_sync_time');
        if ($lastSync && now()->diffInSeconds($lastSync) < 60) {
            return;
        }

        try {
            // 3. Call API (Short timeout so app doesn't freeze if offline)
            $apiUrl = rtrim(config('app.api_url', 'http://localhost:8000'), '/');
            $response = Http::withToken($tokenSetting->value)
                ->timeout(5) 
                ->get($apiUrl . '/api/services/sync');

            if ($response->successful()) {
                $categories = $response->json();
                Log::info('Sync successful, received ' . count($categories) . ' categories');

                foreach ($categories as $catData) {
                    Category::updateOrCreate(
                        ['id' => $catData['id']],
                        ['name' => $catData['name']]
                    );

                    if (isset($catData['services'])) {
                        foreach ($catData['services'] as $serviceData) {
                            Service::updateOrCreate(
                                ['id' => $serviceData['id']],
                                [
                                    'name' => $serviceData['name'],
                                    'url'  => $serviceData['url'],
                                    'icon' => $serviceData['icon'],
                                    'category_id' => $catData['id'],
                                ]
                            );
                        }
                    }
                }
                
                // Update the last sync time
                session(['last_sync_time' => now()]);
            } else {
                Log::warning('Sync failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            // If offline, just log it and move on. Do not crash the app.
            Log::info("Sync skipped: " . $e->getMessage());
        }
    }
}