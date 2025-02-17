<?php

namespace App\Http\Controllers;

use App\Services\PeopleService;
use App\Services\PlanetService;
use App\Services\SpecieService;
use App\Services\SwapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    protected $swapiService;
    protected $planetService;
    protected $specieService;
    protected $peopleService;

    public function __construct(SwapiService $swapiService, PlanetService $planetService, SpecieService $specieService, PeopleService $peopleService)
    {
        $this->swapiService = $swapiService;
        $this->planetService = $planetService;
        $this->specieService = $specieService;
        $this->peopleService = $peopleService;
    }

    public function manageData()
    {
        try {
            $planets = $this->swapiService->getPlanets();
            $species = $this->swapiService->getSpecies();
            $people = $this->swapiService->getPeople();
            $vehicles = $this->swapiService->getVehicles();
            $films = $this->swapiService->getFilms();

            $this->planetService->storeMany($planets);
            $this->specieService->storeMany($species);
            $this->peopleService->storeMany($people);


            $response = ['status' => false, 'message' => 'Data cargada correctamente'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
