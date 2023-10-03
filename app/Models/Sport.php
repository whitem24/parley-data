<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'apiId','name','uid','slug','ref','shortName', 'guid', 'leaguesRef'    
    ];

    public function leagues()
    {
        return $this->hasMany(League::class);
    }

}
