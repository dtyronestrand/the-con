<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class ClearDone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:clear-done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear done someday tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = Task::where('done', true)
            ->whereNull('due_date')
            ->delete();

        $this->info("Cleared done status for {$deletedCount} tasks.");
    }
}
