<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'episode_id',
        'opening_crawl',
        'director',
        'producer',
        'release_date',
        'url'
    ];

    public function planets()
    {
        return $this->belongsToMany(Planet::class, 'films_planets', 'film_url', 'planet_url', 'url', 'url');
    }

    public function people()
    {
        return $this->belongsToMany(People::class, 'films_people', 'film_url', 'people_url', 'url', 'url');
    }

    public function species()
    {
        return $this->belongsToMany(Specie::class, 'films_species', 'film_url', 'specie_url', 'url', 'url');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'films_vehicles', 'film_url', 'vehicle_url', 'url', 'url');
    }
}
