<?php

namespace App\Console\Commands;

use App\Services\RemoteAuthService;
use App\Services\SyncChangeApplier;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

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

        if (! $token) {
            $this->error('API token not configured');

            return 1;
        }

        // No default fallback here: an absent `since` tells the server this
        // is a first sync, returning every current record. Defaulting to
        // e.g. "yesterday" would make a freshly-reinstalled device's first
        // scheduled pull silently miss any remote data older than that.
        $lastSync = cache()->get('last_pull_timestamp');
        $url = rtrim(config('app.api_url'), '/').'/api/services/pull';
        $query = array_filter(['since' => $lastSync]);

        try {
            $response = Http::withToken($token)->timeout(10)->get($url, $query);

            if ($response->status() === 401) {
                $token = $auth->refreshAfterUnauthorized();
                if (! $token) {
                    $this->error('Token refresh failed. Please reconnect.');

                    return 1;
                }
                $response = Http::withToken($token)->timeout(10)->get($url, $query);
            }

            if ($response->failed()) {
                $this->error('Sync failed: '.$response->status());
                $this->error('Response: '.$response->body());

                return 1;
            }
        } catch (ConnectionException $e) {
            $this->warn('Server unreachable, will retry on the next scheduled pull.');

            return 0;
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
