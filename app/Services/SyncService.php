<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SavedLocation;
use App\Models\Category;
use App\Models\Service;
use App\Models\AppSetting;

class SyncService
{
    protected $baseUrl;

    public function __construct(protected RemoteAuthService $auth)
    {
        $this->baseUrl = rtrim(config('app.api_url'), '/');
    }

    public function sync()
    {
        $token = $this->auth->getValidToken();
        if (!$token) {
            Log::error('No API token available for sync.');
            return false;
        }

        // 1. Gather Local Changes (Records updated since last sync)
        // For simplicity, we track the last successful sync time in AppSettings.
        $lastSyncTime = AppSetting::where('key', 'last_sync_timestamp')->value('value');

        $payload = [
            'last_synced_at' => $lastSyncTime,
            'changes' => $this->gatherLocalChanges($lastSyncTime),
        ];

        // 2. Send to Server
        try {
            $response = $this->postSync($token, $payload);

            if ($response->status() === 401) {
                $token = $this->auth->refreshAfterUnauthorized();
                if (!$token) {
                    Log::error('Sync Request Failed: token refresh unsuccessful.');
                    return false;
                }
                $response = $this->postSync($token, $payload);
            }

            if ($response->failed()) {
                Log::error('Sync Request Failed: ' . $response->body());
                return false;
            }

            $serverData = $response->json();

            // 3. Process Server Changes (Pull)
            $this->processServerChanges($serverData['changes'] ?? []);

            // 4. Update Last Sync Timestamp
            AppSetting::updateOrCreate(
                ['key' => 'last_sync_timestamp'],
                ['value' => $serverData['timestamp']]
            );

            return true;

        } catch (\Exception $e) {
            Log::error('Sync Exception: ' . $e->getMessage());
            return false;
        }
    }

    protected function postSync(string $token, array $payload)
    {
        return Http::withToken($token)->post("{$this->baseUrl}/api/sync", $payload);
    }

    protected function gatherLocalChanges($lastSyncTime)
    {
        // If never synced, send everything.
        // If synced before, send only records updated AFTER that time.
        
        $query = function($model) use ($lastSyncTime) {
            return $lastSyncTime 
                ? $model::where('updated_at', '>', $lastSyncTime)->get()
                : $model::all();
        };

        return [
            'categories' => $query(Category::class),
            'services' => $query(Service::class)->map(function($service) {
                // category_id is a local foreign key; send the category's
                // (uuid) id instead so the remote side can resolve it.
                $service->category_uuid = $service->category?->id;
                return $service;
            }),
            'saved_locations' => $query(SavedLocation::class),
        
        ];
    }

    protected function processServerChanges($changes)
    {
        // Similar to the server-side controller, but applying to SQLite
        
        if (!empty($changes['categories'])) {
            foreach ($changes['categories'] as $record) {
                $this->upsertLocal(Category::class, $record);
            }
        }

        if (!empty($changes['services'])) {
            foreach ($changes['services'] as $record) {
                if (isset($record['category_uuid'])) {
                    $record['category_id'] = Category::find($record['category_uuid'])?->id;
                }
                $this->upsertLocal(Service::class, $record);
            }
        }

        if (!empty($changes['saved_locations'])) {
            foreach ($changes['saved_locations'] as $record) {
                $this->upsertLocal(SavedLocation::class, $record);
            }
        }

        if (!empty($changes['app_settings'])) {
            foreach ($changes['app_settings'] as $record) {
                $this->upsertLocal(AppSetting::class, $record);
            }
        }
    }

    protected function upsertLocal($modelClass, $data)
    {
        $uuid = $data['uuid'] ?? null;
        if (!$uuid) return;

        // `uuid` is the wire field name; locally it's the model's own `id`.
        $data['id'] = $uuid;

        // Last Write Wins (Simple version)
        // Ideally, we check timestamps here too, but usually,
        // if the server sent it, it's the "truth".
        $modelClass::firstOrNew(['id' => $uuid])->forceFill($data)->save();
    }
}