<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itineraries extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'duration',
        'image',
        'user_id',
    ];

    public function destinations()
    {
        return $this->hasMany(Destination::class , 'itineraries_id');
    }
}
