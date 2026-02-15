<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\SyncService;
use App\Models\AppSetting;
use Illuminate\Support\Str;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:run {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually trigger sync';

    /**
     * Execute the console command.
     */
    public function handle(SyncService $syncer)
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        if ($email && $password){
        $baseUrl = env('API_URL');
         $this->info("Attempting to log in to {$baseUrl} with email: {$email}");
                 $response = Http::post("{$baseUrl}/api/login", [
            'email' => $email,
            'password' => $password,
        ]);
          if ($response->failed()) {
            $this->error('Login failed: ' . $response->status());
            $this->error('Response: ' . $response->body());
            return;
        }
     $token = $response->json('token');
     AppSetting::updateOrCreate(
            ['key' => 'api_token'],
            ['value' => $token, 'uuid' => (string) Str::uuid()]
        );
          $this->info('Login successful, token received.');
              $syncer->setToken($token);
        }else {
            $this->info('No credentials provided, attempting to use existing token...');
        }
        $success = $syncer->sync();
        if ($success) {
            $this->info('Sync completed successfully!');
        } else {
            $this->error('Sync failed. Check logs for details.');
        }
    }
}
