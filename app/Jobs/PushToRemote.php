<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\SyncQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

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
    public function handle(): void
    {
        $endpoint = config('services.remote_api.url') . '/api/sync/push';
        $token = config('services.remote_api.token');
$pendingUpdates = SyncQueue::whereNull('synced_at')->get();
foreach ($pendingUpdates as $update) {
    $response = Http::withToken($token)
        ->timeout(5)
        ->post($endpoint, [
            'model_name' => $update->model_name,
            'model_uuid' => $update->model_uuid,
            'payload' => $update->payload,
            'action' => $update->action,
        ]);
        if ($response->successful()) {
            $update->update(['synced_at' => now()]);
        }
        if($response->failed()) {
            $response->throw();
        }
    }
}
}