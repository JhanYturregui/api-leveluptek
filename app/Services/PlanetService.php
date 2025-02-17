<?php

namespace App\Services;

use App\Models\Planet;

class PlanetService
{
    protected $peopleService;
    protected $filmService;

    public function  __construct(PeopleService $peopleService, FilmService $filmService)
    {
        $this->peopleService = $peopleService;
        $this->filmService = $filmService;
    }

    public function findOne($id)
    {
        return Planet::find($id);
    }

    public function findOneByUrl($url)
    {
        return Planet::where('url', $url)->first();
    }

    public function verifyExists($url)
    {
        return Planet::where('url', $url)->exists();
    }

    public function store($planetSwapi)
    {
        $planet = $this->findOneByUrl($planetSwapi['url']);
        if (!$planet) {
            $planet = Planet::create([
                'name' => $planetSwapi['name'],
                'rotation_period' => $planetSwapi['rotation_period'],
                'orbital_period' => $planetSwapi['orbital_period'],
                'diameter' => $planetSwapi['diameter'],
                'climate' => $planetSwapi['climate'],
                'gravity' => $planetSwapi['gravity'],
                'terrain' => $planetSwapi['terrain'],
                'surface_water' => $planetSwapi['surface_water'],
                'population' => $planetSwapi['population'],
                'url' => $planetSwapi['url'],
            ]);
            $this->storePeople($planetSwapi['residents'], $planetSwapi['url']);
        }

        $this->storeFilms($planetSwapi['films'], $planet);
        return $planet;
    }

    public function storePeople($residents, $planetUrl)
    {
        foreach ($residents as $residentUrl) {
            $people = $this->peopleService->findOneByUrl($residentUrl);
            if ($people) {
                $people->planet_url = $planetUrl;
                $people->save();
            }
        }
    }

    public function storeFilms($films, $planet)
    {
        foreach ($films as $filmUrl) {
            $film = $this->filmService->findOneByUrl($filmUrl);
            if ($film) {
                $planet->films()->sync($filmUrl);
            }
        }
    }
}
