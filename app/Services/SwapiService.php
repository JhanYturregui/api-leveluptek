<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SwapiService
{
    public function findPlanetById($id)
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/planets/' . $id);
        $result = $response->json();
        return $result;
    }

    public function findPeopleById($id)
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/people/' . $id);
        $result = $response->json();
        return $result;
    }

    public function findFilmById($id)
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/films/' . $id);
        $result = $response->json();
        return $result;
    }

    public function getPlanets()
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/planets');
        $results = $response->json()['results'];
        return $results;
    }

    public function getSpecies()
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/species');
        $results = $response->json()['results'];
        return $results;
    }

    public function getPeople()
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/people');
        $results = $response->json()['results'];
        return $results;
    }

    public function getVehicles()
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/vehicles');
        $results = $response->json()['results'];
        return $results;
    }

    public function getFilms()
    {
        $response = Http::get(config('constants.SWAPI_BASE_URL') . '/films');
        $results = $response->json()['results'];
        return $results;
    }
}
