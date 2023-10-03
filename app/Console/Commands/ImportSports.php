<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Sport;
use Illuminate\Support\Facades\Http;
use Excel;
use App\Helpers\UniqueSlug;

class ImportSports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all available sports';

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
        $uniqueSportsSlug = UniqueSlug::getUniqueSlug('app/excel/Deportes_y_ligas_ESPN.xlsx', 3); 
        $existingSports = Sport::pluck('slug')->toArray();
        $diffSports = array_diff($uniqueSportsSlug, $existingSports);
    
        if (count($diffSports) > 0) {
            foreach ($diffSports as $slug) {
                $this->info('Processing sport: '.$slug);
                $endpoint = config('endpoints.get.sports').$slug.config('endpoints.get.lang.en-us');
                $responseSport = Http::get($endpoint);
            
                if (isset($responseSport)) {
                    $sport = $responseSport->json();
                    $sportNew = new Sport([
                        'apiId' => $sport['id'],
                        'uid' => $sport['uid'],
                        'guid' => $sport['guid'] ?? null,
                        'ref' => $sport['$ref'],
                        'name' => $sport['name'],
                        'slug' => $sport['slug'],
                        'leaguesRef' => $sport['leagues']['$ref']
                    ]);
                
                    $sportNew->save();
                }
            }
        
            $this->info('Sports have been imported successfully.');
        } else {
            $this->info('All These sports already exist in the current database.');
        }
    }
}
