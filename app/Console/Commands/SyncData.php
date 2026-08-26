<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SyncService;
use App\Services\RemoteAuthService;

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
    public function handle(SyncService $syncer, RemoteAuthService $auth)
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if ($email && $password) {
            $this->info("Attempting to log in with email: {$email}");

            if (! $auth->login($email, $password)) {
                $this->error('Login failed. Check logs for details.');

                return 1;
            }

            $this->info('Login successful, token received.');
        } else {
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
