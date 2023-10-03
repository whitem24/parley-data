<?php

namespace App\Console\Commands;
use App\Models\Sport;
use App\Models\League;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Excel;
use App\Helpers\Slug;
use App\Helpers\SlugWithParent;

/**
 * Class ImportLeagues
 *
 * This class is responsible for importing leagues for each sport by retrieving data from an external ESPN API and saving it to the database.
 */
class ImportLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:leagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import leagues for each sport';

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
        $leaguesSlugWithParent = SlugWithParent::getSlugWithParent('app/excel/Deportes_y_ligas_ESPN.xlsx', 4, 3);
        $existingLeagues = League::pluck('slug')->toArray();
        $diffLeagues = array_filter($leaguesSlugWithParent, function ($row) use ($existingLeagues) {
            return !in_array($row['slug'], $existingLeagues);
        });
    
        if (count($diffLeagues) > 0) {
            foreach ($diffLeagues as $slug) {
                $endpoint = config('endpoints.get.sports') . $slug['slugParent'] . '/leagues/' . $slug['slug'] . config('endpoints.get.lang.en-us');
                $sport = Sport::where('slug', $slug['slugParent'])->first();
                $this->info('Processing league.' . $slug['slug']);
                $responseLeague = Http::get($endpoint);
                if ($responseLeague->successful()) {
                    $league = $responseLeague->json();
                    $leagueNew = new League();
                    $leagueNew->fill([
                        'apiId' => $league['id'],
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
                        'sport_id' => $sport->id,
                    ]);
                    $leagueNew->save();
                }
            }             
        
            $this->info('Leagues have been imported successfully.');
        } else {
            $this->info('All These leagues already exist in the current database.');
        }
    }
}
