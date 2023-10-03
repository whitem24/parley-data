<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sport;
use App\Models\League;
use Illuminate\Support\Facades\Http;

class ImportLeague extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:league {sport_slug} {league_slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $arguments = $this->arguments();
        $sportSlug = $arguments['sport_slug'];
        $leagueSlug = $arguments['league_slug'];

        $endpoint = config('endpoints.get.sports') . $sportSlug . '/leagues/' . $leagueSlug . config('endpoints.get.lang.en-us');
        $this->info('Starting process...');
        $responseLeague = Http::get($endpoint);

        if ($responseLeague->successful()) {
            $endpointSport = config('endpoints.get.sports') . $sportSlug . config('endpoints.get.lang.en-us');
            $responseSport = Http::get($endpointSport);

            if ($responseSport->successful()) {
                $sport = $responseSport->json();
                $this->info('Processing Sport...');
                $sport = Sport::firstOrCreate(
                    ['apiId' => $sport['id']],
                    [
                        'uid' => $sport['uid'],
                        'guid' => $sport['guid'] ?? null,
                        'ref' => $sport['$ref'],
                        'name' => $sport['name'],
                        'slug' => $sport['slug'],
                        'leaguesRef' => $sport['leagues']['$ref']
                    ]
                );

                if (!$sport->wasRecentlyCreated) {
                    $this->info('Sport ' . $sportSlug . ' already exists.');
                } else {
                    $this->info('Sport ' . $sportSlug . ' has been imported successfully.');
                }
            }

            $league = $responseLeague->json();
            $this->info('Processing League...');
            $league = League::firstOrCreate(
                ['apiId' => $league['id']],
                [
                    'uid' => $league['uid'],
                    'guid' => $league['guid'] ?? null,
                    'ref' => $league['$ref'],
                    'name' => $league['name'],
                    'slug' => $league['slug'],
                    'midsizeName' => $league['midsizeName'] ?? null,
                    'alternateId' => $league['alternateId'] ?? null,
                    'abbreviation' => $league['abbreviation'] ?? null,
                    'shortName' => $league['shortName'] ?? null,
                    'isTournament' => $league['isTournament'] ?? null,
                    'seasonsRef' => $league['seasons']['$ref'] ?? null,
                    'sport_id' => $sport->id
                ]
            );

            if (!$league->wasRecentlyCreated) {
                $this->info('League ' . $leagueSlug . ' already exists.');
            } else {
                $this->info('League ' . $leagueSlug . ' has been imported successfully.');
            }
        }
    }
}
