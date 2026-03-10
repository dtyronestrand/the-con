<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
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
        $updatedCount = Task::where('done', true)
            ->whereNull('due_date')
            ->get();
        foreach($updatedCount as $task) {
            $task->delete();
        }
        $this->info("Cleared done status for {$updatedCount->count()} tasks.");
    }
}
