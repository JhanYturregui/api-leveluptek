<?php

namespace App\Services;

use App\Models\Film;
use App\Models\People;
use App\Models\Planet;
use App\Models\Specie;
use App\Models\Vehicle;

class FilmService
{
    public function findOne($id)
    {
        return Film::find($id);
    }

    public function findOneByUrl($url)
    {
        return Film::where('url', $url)->first();
    }

    public function store($filmSwapi)
    {
        $film = $this->findOneByUrl($filmSwapi['url']);
        if (!$film) {
            $film =  Film::create([
                'title' => $filmSwapi['title'],
                'episode_id' => $filmSwapi['episode_id'],
                'opening_crawl' => $filmSwapi['opening_crawl'],
                'director' => $filmSwapi['director'],
                'producer' => $filmSwapi['producer'],
                'release_date' => $filmSwapi['release_date'],
                'url' => $filmSwapi['url'],
            ]);
        }

        $this->storePeople($filmSwapi['characters'], $film);
        $this->storePlanets($filmSwapi['planets'], $film);
        $this->storeVehicles($filmSwapi['vehicles'], $film);
        $this->storeSpecies($filmSwapi['species'], $film);

        return $film;
    }

    public function storePeople($peopleSwapi, $film)
    {
        foreach ($peopleSwapi as $peopleUrl) {
            $people = People::where('url', $peopleUrl)->first();
            if ($people) {
                $film->people()->sync($peopleUrl);
            }
        }
    }

    public function storePlanets($planetsSwapi, $film)
    {
        foreach ($planetsSwapi as $planetUrl) {
            $planet = Planet::where('url', $planetUrl)->first();
            if ($planet) {
                $film->planets()->sync($planetUrl);
            }
        }
    }

    public function storeVehicles($vehiclesSwapi, $film)
    {
        foreach ($vehiclesSwapi as $vehicleUrl) {
            $vehicle = Vehicle::where('url', $vehicleUrl)->first();
            if ($vehicle) {
                $film->vehicles()->sync($vehicleUrl);
            }
        }
    }

    public function storeSpecies($speciesSwapi, $film)
    {
        foreach ($speciesSwapi as $specieUrl) {
            $specie = Specie::where('url', $specieUrl)->first();
            if ($specie) {
                $film->species()->sync($specieUrl);
            }
        }
    }
}
