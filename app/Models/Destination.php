<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'itineraries_id',
        'name' , 
        'logement' ,
        'places_to_visit'
    ];

    protected $casts = [
        'places_to_visit' => 'array',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itineraries::class, 'itineraries_id');
    }
}
