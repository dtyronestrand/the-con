<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class ClearTodayDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:clear-today-due-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set due dates to null at end of day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    
     $updatedCount = Task::whereDate('due_date', Carbon::today())
         ->where('done', false)
         ->update(['due_date' => null]);
     $this->info("Cleared due dates for {$updatedCount} tasks.");
    }
}
