<?php

namespace App\Http\Controllers;

use App\Services\PeopleService;
use App\Services\SwapiService;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    protected $swapiService;
    protected $peopleService;

    public function __construct(SwapiService $swapiService, PeopleService $peopleService)
    {
        $this->swapiService = $swapiService;
        $this->peopleService = $peopleService;
    }

    public function findOne($id)
    {
        try {
            $data = $this->peopleService->findOne($id);
            $response = ['status' => true, 'data' => $data];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    public function store($id)
    {
        try {
            $people = $this->swapiService->findPeopleById($id);
            $this->peopleService->store($people);
            $response = ['status' => true, 'message' => 'Creación correcta'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
