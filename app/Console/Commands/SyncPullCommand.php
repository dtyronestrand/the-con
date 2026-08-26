<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\RemoteAuthService;
use App\Services\SyncChangeApplier;

class SyncPullCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull changes from the remote server and update the local database accordingly.';

    /**
     * Execute the console command.
     */
    public function handle(RemoteAuthService $auth, SyncChangeApplier $applier)
    {
        $token = $auth->getValidToken();

        if (!$token) {
            $this->error('API token not configured');
            return 1;
        }

        $lastSync = cache()->get('last_pull_timestamp', now()->subDay()->toDateTimeString());
        $url = rtrim(config('app.api_url'), '/') . '/api/services/pull';

        $response = Http::withToken($token)->timeout(10)->get($url, ['since' => $lastSync]);

        if ($response->status() === 401) {
            $token = $auth->refreshAfterUnauthorized();
            if (!$token) {
                $this->error('Token refresh failed. Please reconnect.');
                return 1;
            }
            $response = Http::withToken($token)->timeout(10)->get($url, ['since' => $lastSync]);
        }

        if ($response->failed()) {
            $this->error('Sync failed: ' . $response->status());
            $this->error('Response: ' . $response->body());
            return 1;
        }

        $changes = $response->json('changes', []);
        
        if (empty($changes)) {
            $this->info('No changes to sync');
            cache()->put('last_pull_timestamp', now()->toDateTimeString());
            return 0;
        }

        $applier->apply($changes);

        cache()->put('last_pull_timestamp', now()->toDateTimeString());
        $this->info('Sync completed successfully');
        return 0;
    }
}
