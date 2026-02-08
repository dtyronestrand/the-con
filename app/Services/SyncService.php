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
        if (!$tokenSetting) return;

        // 2. Optimization: Don't sync if we just synced less than 1 minute ago
        // (Prevents slowing down the app on every single refresh)
        $lastSync = session('last_sync_time');
        if ($lastSync && now()->diffInSeconds($lastSync) < 60) {
            return;
        }

        try {
            // 3. Call API (Short timeout so app doesn't freeze if offline)
            // Use 10.0.2.2 for Android Emulator, or localhost for Desktop
            $response = Http::withToken($tokenSetting->value)
                ->timeout(2) 
                ->get('http://10.0.2.2:8000/api/sync/services');

            if ($response->successful()) {
                $categories = $response->json();

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
            }
        } catch (\Exception $e) {
            // If offline, just log it and move on. Do not crash the app.
            Log::info("Sync skipped: " . $e->getMessage());
        }
    }
}