<?php

namespace App\Console\Commands;

use App\Jobs\PushToRemote;
use Illuminate\Console\Command;

class SyncPushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush locally queued changes (sync_queue) to the remote server.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Runs inline rather than going through the queue, so a scheduled
        // push doesn't depend on a queue worker being alive — matching how
        // sync:pull already runs.
        PushToRemote::dispatchSync();

        $this->info('Push flush completed.');

        return 0;
    }
}
