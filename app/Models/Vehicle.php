<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'manufacturer',
        'cost_in_credits',
        'length',
        'max_atmosphering_speed',
        'crew',
        'passengers',
        'cargo_capacity',
        'consumables',
        'vehicle_class',
        'url'
    ];

    public function people()
    {
        return $this->belongsToMany(People::class, 'people_vehicles', 'vehicle_url', 'people_url', 'url', 'url');
    }

    public function films()
    {
        return $this->belongsToMany(Film::class, 'films_vehicles', 'vehicle_url', 'film_url', 'url', 'url');
    }
}
