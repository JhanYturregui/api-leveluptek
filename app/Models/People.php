<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'planet_url',
        'name',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'birth_year',
        'gender',
        'homeworld',
        'url'
    ];

    public function planet()
    {
        return $this->belongsTo(Planet::class, 'planet_url', 'url');
    }

    public function species()
    {
        return $this->belongsToMany(Specie::class, 'people_species', 'people_url', 'specie_url', 'url', 'url');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'people_vehicles', 'people_url', 'vehicle_url', 'url', 'url');
    }

    public function films()
    {
        return $this->belongsToMany(Film::class, 'films_people', 'people_url', 'film_url', 'url', 'url');
    }
}
