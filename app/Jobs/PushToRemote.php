<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\SyncQueue;
use App\Services\RemoteAuthService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushToRemote implements ShouldQueue
{
    use Dispatchable,  InteractsWithQueue, Queueable, SerializesModels;

    public $backoff = [30, 60, 12, 600, 3600];

    public $tries = 20;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(RemoteAuthService $auth): void
    {
        $token = $auth->getValidToken();

        if (!$token) {
            Log::error('PushToRemote: no valid API token available.');
            return;
        }

        $endpoint = rtrim(config('app.api_url'), '/') . '/api/sync/push';
        $pendingUpdates = SyncQueue::whereNull('synced_at')->get();

        foreach ($pendingUpdates as $update) {
            $response = $this->push($endpoint, $token, $update);

            if ($response->status() === 401) {
                $token = $auth->refreshAfterUnauthorized();
                if (!$token) {
                    Log::error('PushToRemote: token refresh failed mid-batch.');
                    return;
                }
                $response = $this->push($endpoint, $token, $update);
            }

            if ($response->successful()) {
                $update->update(['synced_at' => now()]);
            } else {
                $response->throw();
            }
        }
    }

    protected function push(string $endpoint, string $token, SyncQueue $update)
    {
        return Http::withToken($token)
            ->timeout(5)
            ->post($endpoint, [
                'model_name' => $update->model_name,
                'model_uuid' => $update->model_uuid,
                'payload' => $update->payload,
                'action' => $update->action,
            ]);
    }
}