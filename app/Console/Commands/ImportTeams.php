<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sport;
use App\Models\League;
use App\Models\Team;

/**
 * Class ImportTeams
 *
 * This class is responsible for importing teams from an external API and storing them in the database.
 */
class ImportTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import teams';

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
        $leagues = League::select('id', 'slug', 'sport_id')->get();
    
        foreach ($leagues as $league) {
            $sport = Sport::select('slug')->first();
            $endpoint = config('endpoints.get.sportsSite').$sport->slug.'/'.$league->slug.'/teams';
            $responseLeagues = Http::get($endpoint);
        
            if($responseLeagues->successful()){
                $leagues = $responseLeagues->json();
            
                if(isset($leagues['sports'])){
                    foreach ($leagues['sports'][0]['leagues'][0]['teams'] as $item) {
                        $this->info('Processing team. '.$item['team']['name']);
                    
                        $team = Team::firstOrCreate(
                            ['apiId' => $item['team']['id']],
                            [
                                'uid' => $item['team']['uid'],
                                'name' => $item['team']['name'],
                                'slug' => $item['team']['slug'],
                                'abbreviation' => $item['team']['abbreviation'] ?? null,
                                'displayName' => $item['team']['displayName'],
                                'shortDisplayName' => $item['team']['shortDisplayName'],
                                'nickname' => $item['team']['nickname'] ?? null,
                                'location' => $item['team']['location'],
                                'color' => $item['team']['color'] ?? null,
                                'alternateColor' => $item['team']['alternateColor'] ?? null,
                                'isActive' => $item['team']['isActive'] ?? null,
                                'isAllStar' => $item['team']['isAllStar'] ?? null
                            ]
                        );

                        if($team){
                            $team->leagues()->attach($league->id);
                        }
                    };
                }
            }
        }
    
        $this->info('Teams have been imported successfully.');
    }
}
              
