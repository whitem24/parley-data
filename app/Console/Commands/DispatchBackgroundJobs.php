<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportSportsJob;
use App\Jobs\ImportLeaguesJob;
use App\Jobs\ImportSeasonsJob;
use App\Jobs\ImportTeamsJob;

class DispatchBackgroundJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:imports-jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatching imports with Jobs files in background';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info("Dispatching jobs...");
            ImportSportsJob::dispatch();
            ImportLeaguesJob::dispatch();
            ImportSeasonsJob::dispatch();
            ImportTeamsJob::dispatch();
            $this->info("Processing imports...");
            \Artisan::call('queue:work --stop-when-empty');
            $this->info("Job imports have been done successfully.");
        } catch (\Throwable $th) {
            $this->info($th);
        }
    }
}
