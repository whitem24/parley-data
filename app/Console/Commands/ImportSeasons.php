<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sport;
use App\Models\League;
use App\Models\Season;
use Illuminate\Support\Facades\Http;

/**
 * Class ImportSeasons
 *
 * This class is a command class in a Laravel application that imports seasons for different leagues.
 * It retrieves the leagues from the database, makes HTTP requests to the provided endpoints for each league,
 * and saves the seasons data into the database.
 *
 * @package App\Console\Commands
 */
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
            $endpoint = $league->seasonsRef;
            $responseSeasons = Http::get($endpoint);
            if (isset($responseSeasons)) {
                $seasons = $responseSeasons->json();
                if (isset($seasons['items'])) {
                    foreach ($seasons['items'] as $season) {
                        $endpointSeason = $season['$ref'];
                        $responseSeason = Http::get($endpointSeason);
                        if (isset($responseSeason)) {
                            $season = $responseSeason->json();
                            $this->info('Processing season. ' . $season['year']);
                            $season = Season::firstOrCreate(
                                ['ref' => $season['$ref']],
                                [
                                    'year' => $season['year'],
                                    'startDate' => $season['startDate'],
                                    'endDate' => $season['endDate'],
                                    'displayName' => isset($season['displayName']) ? $season['displayName'] : null,
                                    'shortDisplayName' => isset($season['shortDisplayName']) ? $season['shortDisplayName'] : null,
                                    'abbreviation' => isset($season['abbreviation']) ? $season['abbreviation'] : null,
                                    'league_id' => $league->id,
                                ]
                            );
                        }
                    }
                    $this->info($league->name . ' seasons have been imported successfully.');
                }
            }
        }
        $this->info('Process completed successfully.');
    }
}