<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Treatment;
use App\Models\MaterialTreatment;

class TreatmentController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    date_default_timezone_set('America/Lima');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $pageTitle = 'Tratamientos';
    $route = 'treatments';
    return view('pages.treatments.index', compact('pageTitle', 'route'));
  }

  /**
   * Get data to show in datatables
   *
   * @return json
   */
  public function getData()
  {
    $query = Treatment::select(
      'id',
      'name',
      'price',
      DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
      DB::raw("DATE_FORMAT(updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted")
    )
      ->with('materials')
      ->orderBy('id', 'desc');

    return datatables()
      ->eloquent($query)
      ->addColumn('col-actions', 'pages.treatments.columns.actions')
      ->addColumn('materials', function ($query) {
        return $query->materials->pluck('name')->implode(', ');
      })
      ->rawColumns(['col-actions', 'materials'])
      ->toJson();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $pageTitle = 'Nuevo tratamiento';
    return view('pages.treatments.create', compact('pageTitle'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = array();
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['price'] = $request->input('price');
    $materials = $request->input('materialsList');

    DB::beginTransaction();
    try {
      $treatment = Treatment::create($data);

      $materialsList = [];
      foreach ($materials as $material) {
        $materialsList[$material['id']] = ['amount' => $material['amount']];
      }
      $treatment->materials()->attach($materialsList);
      DB::commit();

      $response = [
        'status' => true,
        'message' => 'Registro correcto.',
      ];
    } catch (\Exception $e) {
      DB::rollBack();
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $treatment = Treatment::with('materials')->find($id);
    $pageTitle = 'Editar Tratamiento';

    return view('pages.treatments.edit', compact('treatment', 'pageTitle'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request)
  {
    $id = intval($request->input('id'));
    $data = array();
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['price'] = $request->input('price');
    $materials = $request->input('materialsList');

    DB::beginTransaction();
    try {
      $treatment = Treatment::find($id);
      $treatment->update($data);

      $materialsList = [];
      foreach ($materials as $material) {
        $materialsList[$material['id']] = ['amount' => $material['amount']];
      }
      $treatment->materials()->sync($materialsList);

      DB::commit();

      $response = ['status' => true, 'message' => 'Actualización correcta.'];
    } catch (\Exception $e) {
      DB::rollBack();
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }

  /**
   * Delete resource
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function delete(Request $request)
  {
    $id = $request->input('id');
    $response = array();

    try {
      $treatment = Treatment::findOrFail($id);
      $treatment->delete();

      $response = ['status' => true, 'message' => 'Eliminación correcta.'];
    } catch (\Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }
    return json_encode($response);
  }
}
