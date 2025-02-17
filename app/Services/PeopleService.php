<?php

namespace App\Services;

use App\Models\People;
use App\Models\Planet;

class PeopleService
{
    protected $filmService;
    protected $specieService;

    public function __construct(FilmService $filmService, SpecieService $specieService)
    {
        $this->filmService = $filmService;
        $this->specieService = $specieService;
    }

    public function findOne($id)
    {
        return People::find($id);
    }

    public function findOneByUrl($url)
    {
        return People::where('url', $url)->first();
    }

    public function store($peopleSwapi)
    {
        $people = $this->findOneByUrl($peopleSwapi['url']);
        if (!$people) {
            $planetUrl = Planet::where('url', $peopleSwapi['homeworld'])->exists() ? $peopleSwapi['homeworld'] : null;
            $people =  People::create([
                'planet_url' => $planetUrl,
                'name' => $peopleSwapi['name'],
                'height' => $peopleSwapi['height'],
                'mass' => $peopleSwapi['mass'],
                'hair_color' => $peopleSwapi['hair_color'],
                'skin_color' => $peopleSwapi['skin_color'],
                'eye_color' => $peopleSwapi['eye_color'],
                'birth_year' => $peopleSwapi['birth_year'],
                'gender' => $peopleSwapi['gender'],
                'homeworld' => $peopleSwapi['homeworld'],
                'url' => $peopleSwapi['url'],
            ]);
        }

        $this->storeFilms($peopleSwapi['films'], $people);
        $this->storeSpecies($peopleSwapi['species'], $people);
        return $people;
    }

    public function storeFilms($filmsSwapi, $people)
    {
        foreach ($filmsSwapi as $filmUrl) {
            $film = $this->filmService->findOneByUrl($filmUrl);
            if ($film) {
                $people->films()->sync($filmUrl);
            }
        }
    }

    public function storeSpecies($speciesSwapi, $people)
    {
        foreach ($speciesSwapi as $specieUrl) {
            $specie = $this->specieService->findOneByUrl($specieUrl);
            if ($specie) {
                $people->species()->sync($specieUrl);
            }
        }
    }
}
