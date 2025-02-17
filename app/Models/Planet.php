<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rotation_period',
        'orbital_period',
        'diameter',
        'climate',
        'gravity',
        'terrain',
        'surface_water',
        'population',
        'url'
    ];

    public function people()
    {
        return $this->hasMany(People::class, 'planet_url', 'url');
    }

    public function films()
    {
        return $this->belongsToMany(Film::class, 'films_planets', 'planet_url', 'film_url', 'url', 'url');
    }
}
