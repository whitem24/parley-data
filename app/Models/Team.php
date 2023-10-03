<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'apiId','name','uid','slug','abbreviation','shortName', 'displayName', 'shortDisplayName', 'nickname', 'location', 'color', 'alternateColor', 'isActive', 'isAllStar'
    ];

    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_teams');
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'team_seasons');
    }
}
