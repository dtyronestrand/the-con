<?php

namespace App\Http\Controllers;

use App\Services\RemoteAuthService;
use App\Services\SyncChangeApplier;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SyncController extends Controller
{
    public function sync(RemoteAuthService $auth, SyncChangeApplier $applier)
    {
        // 1. Get a valid token (refreshing first if needed)
        $token = $auth->getValidToken();

        if (! $token) {
            return back()->withErrors(['msg' => 'No API token found. Please login first.']);
        }

        // 2. Call the Server
        $apiUrl = rtrim(config('app.api_url'), '/');
        $url = $apiUrl.'/api/services/pull';

        try {
            $response = Http::withToken($token)->timeout(10)->get($url);

            if ($response->status() === 401) {
                $token = $auth->refreshAfterUnauthorized();
                if (! $token) {
                    return back()->withErrors(['msg' => 'Session expired. Please reconnect.']);
                }
                $response = Http::withToken($token)->timeout(10)->get($url);
            }

            if ($response->failed()) {
                return back()->withErrors(['msg' => 'Sync failed: '.$response->status()]);
            }
        } catch (ConnectionException $e) {
            return back()->withErrors(['msg' => 'Could not reach the server. Check your connection and try again.']);
        }

        // 3. Process the Data
        $applier->apply($response->json('changes', []));

        return back()->with('status', 'Services synced successfully!');
    }
}
