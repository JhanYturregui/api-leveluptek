<?php

namespace App\Http\Controllers;

use App\Services\PlanetService;
use App\Services\SwapiService;
use Illuminate\Http\Request;

class PlanetController extends Controller
{
    protected $swapiService;
    protected $planetService;

    public function __construct(SwapiService $swapiService, PlanetService $planetService)
    {
        $this->swapiService = $swapiService;
        $this->planetService = $planetService;
    }

    public function findOne($id)
    {
        try {
            $data = $this->planetService->findOne($id);
            $response = ['status' => true, 'data' => $data];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    public function store($id)
    {
        try {
            $planet = $this->swapiService->findPlanetById($id);
            $this->planetService->store($planet);
            $response = ['status' => true, 'message' => 'Creación correcta'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
