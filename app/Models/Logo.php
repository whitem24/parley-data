<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = [
        'href', 'alt', 'width', 'height', 'logoeable_id', 'logoeable_type'     
    ];

    public function logoeable()
    {
        return $this->morphTo();
    }
}
