<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sport;
use App\Models\League;
use App\Models\Season;
use Illuminate\Support\Facades\Http;

class ImportSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:seasons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the seasons by league';

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
        $leagues = League::all();
        $this->info('Starting Process.');

        foreach ($leagues as $league) {
            $sport = Sport::where('id', $league->sport_id)->first();
            $responseSeasons = Http::get($league->seasonsRef);

            if ($responseSeasons->successful()) {
                $seasons = $responseSeasons->json();

                if (isset($seasons['items'])) {
                    foreach ($seasons['items'] as $season) {
                        $responseSeason = Http::get($season['$ref']);

                        if ($responseSeason->successful()) {
                            $season = $responseSeason->json();
                            $this->info('Processing season. '.$season['year']);

                            $season = Season::firstOrCreate(
                                ['ref' => $season['$ref']],
                                [
                                    'year' => $season['year'],
                                    'startDate' => $season['startDate'],
                                    'endDate' => $season['endDate'],
                                    'displayName' => $season['displayName'] ?? null,
                                    'shortDisplayName' => $season['shortDisplayName'] ?? null,
                                    'abbreviation' => $season['abbreviation'] ?? null,
                                    'league_id' => $league->id,
                                ]
                            );
                        }
                    }
                }

                $this->info($league->name.' seasons have been imported successfully.');
            }
        }

        $this->info('Process completed successfully.');
    }
}
