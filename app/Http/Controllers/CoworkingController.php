<?php

namespace App\Http\Controllers;

use App\Models\Coworking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CoworkingController extends Controller {
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('auth');
    date_default_timezone_set('America/Lima');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    return view('pages.coworkings.index');
  }

  /**
  * Get data to show in datatables
  *
  * @return json
  */
  public function getData() {
    $query = DB::table('coworkings')
              ->select('id', 'name', 'description', 'created_at', 'updated_at')
              ->orderBy('id', 'desc');

    return datatables()
      ->queryBuilder($query)
      ->addColumn('col-actions', 'pages.coworkings.columns.actions')
      ->rawColumns(['col-actions'])
      ->toJson();
  }

  /**
   * Function to validate data for register or update functions
   * 
   * @param Array $datos
   * @param int $id (null ? registrar : actualizar )
   * @return \Illuminate\Http\Response
   */
  public function validateData($data, $id) {
    $response = array();

    if ($id) {
      if (!Coworking::find($id)) {
        $response = ['status' => false, 'mensaje' => 'El registro no existe.'];
        return $response;
      }
    }

    $unique = $id ? 'unique:coworkings,name,'. $id : 'unique:coworkings';
    $rules = ['name' => 'bail|required|'.$unique];

    $messages = [
      'name.required'  => 'El campo NOMBRE es obligatorio.',
      'name.unique'    => 'El NOMBRE ya está registrado.',
    ];

    $validator = Validator::make($data, $rules, $messages);  
    if ($validator->fails()) {
      $response = ['status' => false, 'message' => $validator->messages()->first()];
    } else {
      $response = ['status' => true];
    }

    return $response;
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('pages.coworkings.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $data = array();
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['description'] = $request->input('description');

    $response = $this->validateData($data, null);
    if (!$response['status']) return json_encode($response);

    try {
      $coworking = Coworking::create($data);

      $response = [
        'status' => true,
        'message' => 'Registro correcto.',
      ];

    } catch (Exception $e) {
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
  public function edit($id) {
    $coworking = Coworking::find($id);
    return view('pages.coworkings.edit', compact('coworking'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request){
    $id = intval($request->input('id'));
    $data = array();
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['description'] = $request->input('description');

    $response = $this->validateData($data, $id);
    if (!$response['status']) return json_encode($response);

    try {
      $coworking = Coworking::find($id);
      $coworking->update($data);
      $response = ['status' => true, 'message' => 'Actualización correcta.'];

    } catch (Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }

  /**
  * Eliminar un registro (inactivar)
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function delete(Request $request) {
    $id = $request->input('id');
    $response = array();

    try {
      $coworking = Coworking::findOrFail($id);
      $coworking->delete();

      $response = ['status' => true, 'message' => 'Eliminación correcta.'];

    } catch (Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }
    return json_encode($response);
  }
}
