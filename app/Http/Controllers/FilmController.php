<?php

namespace App\Http\Controllers;

use App\Services\SwapiService;
use App\Services\FilmService;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    protected $swapiService;
    protected $filmService;

    public function __construct(SwapiService $swapiService, FilmService $filmService)
    {
        $this->swapiService = $swapiService;
        $this->filmService = $filmService;
    }

    public function findOne($id)
    {
        try {
            $data = $this->filmService->findOne($id);
            $response = ['status' => true, 'data' => $data];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    public function store($id)
    {
        try {
            $film = $this->swapiService->findFilmById($id);
            $this->filmService->store($film);
            $response = ['status' => true, 'message' => 'Creación correcta'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
