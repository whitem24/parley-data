<?php

use App\Console\Commands\ImportLeague;
use App\Models\Sport;
use App\Models\League;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportLeagueTest extends TestCase
{
    use RefreshDatabase;

    public function testImportNewSportAndLeague()
    {
        Http::fake([
            config('endpoints.get.sports') . 'soccer' . '/leagues/' . 'uefa.champions' . config('endpoints.get.lang.en-us') => Http::response(
                ['id' => '775', 
                'uid' => 's:600~l:775', 
                '$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues/uefa.champions?lang=en&region=us', 
                'name' => 'UEFA Champions League', 
                'slug' => 'uefa.champions'],
             200),
            config('endpoints.get.sports') . 'soccer' . config('endpoints.get.lang.en-us') => Http::response(
                ['id' => '600', 
                'uid' => 's:600', 
                '$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer?lang=en&region=us', 
                'name' => 'Soccer', 
                'slug' => 'soccer', 
                'leagues' => ['$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues?lang=en&region=us']],
             200),
        ]);
        
        $this->artisan('import:league', ['sport_slug' => 'soccer', 'league_slug' => 'uefa.champions'])
            ->expectsOutput('Starting process...')
            ->expectsOutput('Processing Sport...')
            ->expectsOutput('Sport soccer has been imported successfully.')
            ->expectsOutput('Processing League...')
            ->expectsOutput('League uefa.champions has been imported successfully.')
            ->assertExitCode(0);

        $this->assertDatabaseHas('sports', ['apiId' => '600', 'uid' => 's:600', 'name' => 'Soccer', 'slug' => 'soccer', 'leaguesRef' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues?lang=en&region=us']);
        $this->assertDatabaseHas('leagues', ['apiId' => '775', 'uid' => 's:600~l:775', 'name' => 'UEFA Champions League', 'slug' => 'uefa.champions', 'sport_id' => 1]);
    }

    public function testImportExistingSportAndLeague()
    {
        $sport = Sport::create(['apiId' => '600', 'uid' => 's:600','ref' => 'http://sports.core.api.espn.com/v2/sports/soccer?lang=en&region=us', 'name' => 'Soccer', 'slug' => 'soccer', 'leaguesRef' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues?lang=en&region=us']);
        $league = League::create(['apiId' => '775', 'uid' => 's:600~l:775','ref' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues/uefa.champions?lang=en&region=us', 'name' => 'UEFA Champions League', 'slug' => 'uefa.champions', 'sport_id' => $sport->id]);

        Http::fake([
            config('endpoints.get.sports') . 'soccer' . '/leagues/' . 'uefa.champions' . config('endpoints.get.lang.en-us') => Http::response(
                ['id' => '775', 
                'uid' => 's:600~l:775', 
                '$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues/uefa.champions?lang=en&region=us', 
                'name' => 'UEFA Champions League', 
                'slug' => 'uefa.champions'],
             200),
            config('endpoints.get.sports') . 'soccer' . config('endpoints.get.lang.en-us') => Http::response(
                ['id' => '600', 
                'uid' => 's:600', 
                '$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer?lang=en&region=us', 
                'name' => 'Soccer', 
                'slug' => 'soccer', 
                'leagues' => ['$ref' => 'http://sports.core.api.espn.com/v2/sports/soccer/leagues?lang=en&region=us']],
             200),
        ]);

        $this->artisan('import:league', ['sport_slug' => 'soccer', 'league_slug' => 'uefa.champions'])
            ->expectsOutput('Starting process...')
            ->expectsOutput('Processing Sport...')
            ->expectsOutput('Sport soccer already exists.')
            ->expectsOutput('Processing League...')
            ->expectsOutput('League uefa.champions already exists.')
            ->assertExitCode(0);

        $this->assertCount(1, Sport::all());
        $this->assertCount(1, League::all());
    }
}