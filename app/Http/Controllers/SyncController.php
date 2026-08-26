<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Services\RemoteAuthService;
use App\Services\SyncChangeApplier;

class SyncController extends Controller
{
    public function sync(RemoteAuthService $auth, SyncChangeApplier $applier)
    {
        // 1. Get a valid token (refreshing first if needed)
        $token = $auth->getValidToken();

        if (!$token) {
            return back()->withErrors(['msg' => 'No API token found. Please login first.']);
        }

        // 2. Call the Server
        $apiUrl = rtrim(config('app.api_url'), '/');
        $url = $apiUrl . '/api/services/pull';

        $response = Http::withToken($token)->timeout(10)->get($url);

        if ($response->status() === 401) {
            $token = $auth->refreshAfterUnauthorized();
            if (!$token) {
                return back()->withErrors(['msg' => 'Session expired. Please reconnect.']);
            }
            $response = Http::withToken($token)->timeout(10)->get($url);
        }

        if ($response->failed()) {
            return back()->withErrors(['msg' => 'Sync failed: ' . $response->status()]);
        }

        // 3. Process the Data
        $applier->apply($response->json('changes', []));

        return back()->with('status', 'Services synced successfully!');
    }
}