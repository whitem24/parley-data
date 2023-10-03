<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;
use App\Jobs\ImportSportsJob;
use App\Jobs\ImportLeaguesJob;
use App\Jobs\ImportSeasonsJob;
use App\Jobs\ImportTeamsJob;

class DispatchBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:imports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch batch jobs for all the imports';

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
            $batch = Bus::batch([
                new ImportSportsJob(),
                new ImportLeaguesJob(),
                new ImportSeasonsJob(),
                new ImportTeamsJob(),
            ])->dispatch();
        
            $this->info("Processing imports...");
            \Artisan::call('queue:work --stop-when-empty');
        
            $this->info("Batch imports have been done successfully.");
        } catch (\Throwable $th) {
            $this->info($th);
        }
    }

   
}

