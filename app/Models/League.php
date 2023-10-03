<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'apiId','name','uid','slug','guid','ref','midsizeName','alternateId','abbreviation','shortName','isTournament', 'seasonsRef', 'sport_id'      
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'league_teams');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }
}
