<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classification',
        'designation',
        'average_height',
        'skin_colors',
        'hair_colors',
        'eye_colors',
        'average_lifespan',
        'homeworld',
        'language',
        'url'
    ];

    public function people()
    {
        return $this->belongsToMany(People::class, 'people_species', 'specie_url', 'people_url', 'url', 'url');
    }

    public function films()
    {
        return $this->belongsToMany(Film::class, 'films_species', 'specie_url', 'film_url', 'url', 'url');
    }
}
