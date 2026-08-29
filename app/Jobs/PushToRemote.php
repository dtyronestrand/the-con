<?php

namespace App\Jobs;

use App\Models\SyncQueue;
use App\Services\RemoteAuthService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
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

        if (! $token) {
            Log::error('PushToRemote: no valid API token available.');

            return;
        }

        $endpoint = rtrim(config('app.api_url'), '/').'/api/services/push';
        $pendingUpdates = SyncQueue::whereNull('synced_at')->get();

        foreach ($pendingUpdates as $update) {
            try {
                $response = $this->push($endpoint, $token, $update);

                if ($response->status() === 401) {
                    $token = $auth->refreshAfterUnauthorized();
                    if (! $token) {
                        Log::error('PushToRemote: token refresh failed mid-batch.');

                        return;
                    }
                    $response = $this->push($endpoint, $token, $update);
                }

                if ($response->successful()) {
                    $update->update(['synced_at' => now()]);
                } else {
                    // Log and move on to the next queued row rather than
                    // aborting the batch — one bad row (a permanent 422, a
                    // dropped connection) shouldn't block every other
                    // pending change from syncing. It stays unsynced and
                    // gets retried on the next scheduled push.
                    Log::warning("PushToRemote: push failed for {$update->model_name}#{$update->model_uuid}: {$response->status()} {$response->body()}");
                }
            } catch (\Throwable $e) {
                Log::error("PushToRemote: exception pushing {$update->model_name}#{$update->model_uuid}: {$e->getMessage()}");
            }
        }
    }

    protected function push(string $endpoint, string $token, SyncQueue $update)
    {
        return Http::withToken($token)
            ->timeout(5)
            ->post($endpoint, [
                'table' => $update->model_name,
                'model_uuid' => $update->model_uuid,
                'payload' => $update->payload,
                'action' => $update->action,
            ]);
    }
}
