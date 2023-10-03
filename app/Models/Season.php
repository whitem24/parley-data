<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'ref','year','startDate', 'endDate', 'displayName', 'shortDisplayName', 'abbreviation', 'league_id'
    ];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_seasons');
    }

}
