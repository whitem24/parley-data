<?php

namespace App\Jobs;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ImportLeaguesJob
 *
 * This class implements the ShouldQueue interface and represents a job for importing leagues.
 * It includes a constructor and a handle method.
 */
class ImportLeaguesJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * This method calls the Artisan facade to execute the import:leagues command.
     *
     * @return void
     */
    public function handle()
    {
        \Artisan::call('import:leagues');
    }
}
