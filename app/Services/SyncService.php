<?php

namespace App\Services;

use App\Jobs\PushToRemote;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncService
{
    public function __construct(
        protected RemoteAuthService $auth,
        protected SyncChangeApplier $applier,
    ) {}

    /**
     * Pull remote changes into the local database, then flush any locally
     * queued changes back to the remote server. Runs the push synchronously
     * (rather than via the queue) so callers get an immediate, complete sync.
     */
    public function sync(): bool
    {
        $pulled = $this->pull();

        PushToRemote::dispatchSync();

        return $pulled;
    }

    /**
     * True when there's no usable API token — either the app was never
     * connected to a server, or a 401 during the sync above caused the last
     * token to be dropped. Call this *after* sync(). Distinct from a failed
     * sync caused by the server simply being unreachable, which is the
     * expected, silent case for an offline-first app.
     */
    public function needsReconnect(): bool
    {
        return $this->auth->getValidToken() === null;
    }

    protected function pull(): bool
    {
        $token = $this->auth->getValidToken();

        if (! $token) {
            return false;
        }

        $url = rtrim(config('app.api_url'), '/').'/api/services/pull';
        $lastSync = cache()->get('last_pull_timestamp');

        try {
            $response = Http::withToken($token)->timeout(10)->get($url, array_filter(['since' => $lastSync]));

            if ($response->status() === 401) {
                $token = $this->auth->refreshAfterUnauthorized();

                if (! $token) {
                    return false;
                }

                $response = Http::withToken($token)->timeout(10)->get($url, array_filter(['since' => $lastSync]));
            }

            if ($response->failed()) {
                Log::warning('SyncService: pull failed with status '.$response->status());

                return false;
            }
        } catch (ConnectionException $e) {
            // Server unreachable — normal for an offline-first app, not an error.
            return false;
        }

        $this->applier->apply($response->json('changes', []));
        cache()->put('last_pull_timestamp', now()->toDateTimeString());

        return true;
    }
}
