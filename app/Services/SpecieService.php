<?php

namespace App\Services;

use App\Models\Specie;

class SpecieService
{
    public function findOne($id)
    {
        return Specie::find($id);
    }

    public function findOneByUrl($url)
    {
        return Specie::where('url', $url)->first();
    }

    public function storeMany($species)
    {
        foreach ($species as $specie) {
            Specie::create([
                'name' => $specie['name'],
                'classification' => $specie['classification'],
                'designation' => $specie['designation'],
                'average_height' => $specie['average_height'],
                'skin_colors' => $specie['skin_colors'],
                'hair_colors' => $specie['hair_colors'],
                'eye_colors' => $specie['eye_colors'],
                'average_lifespan' => $specie['average_lifespan'],
                'homeworld' => $specie['homeworld'],
                'language' => $specie['language'],
                'swapi_url' => $specie['url'],
            ]);
        }
    }
}
