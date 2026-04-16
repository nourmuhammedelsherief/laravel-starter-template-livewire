<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Task;

class ProcessTask implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        logger("Processing task: " . $this->task->title);
    }
}
