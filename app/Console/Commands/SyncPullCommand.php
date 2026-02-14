<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\AppSetting;

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
    public function handle()
    {
        $tokenSetting = AppSetting::where('key', 'api_token')->first();
        
        if (!$tokenSetting) {
            $this->error('API token not configured');
            return 1;
        }
        
        $lastSync = cache()->get('last_pull_timestamp', now()->subDay()->toDateTimeString());

        $response = Http::withToken($tokenSetting->value)
            ->timeout(10)
            ->get(env('API_URL') . '/api/services/pull', [
                'since' => $lastSync,
            ]);

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

        DB::transaction(function () use ($changes) {
            foreach ($changes as $table => $rows) {
                foreach ($rows as $row) {
                    $modelClass = $this->getModelClassFromTable($table);
                    $record = $modelClass::withTrashed()->findOrNew($row['id']);
                    
                    // Prevent local observers from firing and creating an infinite loop
                    $record->forceFill($row);
                    $record->is_syncing = true; 
                    
                    if (isset($row['deleted_at']) && $row['deleted_at']) {
                        $record->save(); // Save first to ensure it exists
                        $record->delete(); // Then soft delete
                    } else {
                        $record->save();
                    }
                }
            }
        });
        
        cache()->put('last_pull_timestamp', now()->toDateTimeString());
        $this->info('Sync completed successfully');
        return 0;
    }
    private function getModelClassFromTable($table) {
        // Map table names to Models, or use a convention
        return 'App\\Models\\' . str($table)->singular()->studly();
    }
}
