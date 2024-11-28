<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Stock;

class MaterialController extends Controller
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
    $pageTitle = 'Materiales';
    $route = 'materials';

    return view('pages.materials.index', compact('pageTitle', 'route'));
  }

  /**
   * Get data to show in datatables
   *
   * @return json
   */
  public function getData()
  {

    $query = Material::from('materials as m')
      ->join('categories as c', 'm.category_id', 'c.id')
      ->join('units as u', 'm.unit_id', 'u.id')
      ->select(
        'm.id',
        'm.type',
        'm.code',
        'm.name',
        'm.brand',
        'm.price',
        'm.price_sale',
        'm.price_total',
        DB::raw("DATE_FORMAT(m.created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
        DB::raw("DATE_FORMAT(m.updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted"),
        'c.name as category_name',
        'u.name as unit_name'
      )
      ->orderBy('m.id', 'desc');

    return datatables()
      ->eloquent($query)
      ->addColumn('col-actions', 'pages.materials.columns.actions')
      ->rawColumns(['col-actions'])
      ->toJson();
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $categories = Category::all();
    $units = Unit::all();
    $pageTitle = 'Nuevo Material';

    return view('pages.materials.create', compact('categories', 'units', 'pageTitle'));
  }

  /**
   * Function to validate data for register or update functions
   * 
   * @param Array $datos
   * @param int $id (null ? registrar : actualizar )
   * @return \Illuminate\Http\Response
   */
  public function validateData($data, $id)
  {
    $response = array();

    if ($id) {
      if (!Material::find($id)) {
        $response = ['status' => false, 'mensaje' => 'El registro no existe.'];
        return $response;
      }
    }

    $unique = $id ? 'unique:materials,code,' . $id : 'unique:materials';
    $rules = [
      'code' => 'bail|required|' . $unique,
      'name' => 'bail|required'
    ];

    $messages = [
      'code.required'  => 'El campo CÓDIGO es obligatorio.',
      'code.unique'    => 'El CÓDIGO ya está registrado.',
      'name.required'  => 'El campo NOMBRE es obligatorio.',
    ];

    $validator = Validator::make($data, $rules, $messages);
    if ($validator->fails()) {
      $response = ['status' => false, 'message' => $validator->errors()->first()];
    } else {
      $response = ['status' => true];
    }

    return $response;
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
    $data['category_id'] = $request->input('category');
    $data['unit_id'] = $request->input('unit');
    $data['type'] = $request->input('type');
    $data['code'] = mb_strtoupper($request->input('code'));
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['brand'] = mb_strtoupper($request->input('brand'));
    $data['price'] = $request->input('price');
    $data['price_sale'] = $request->input('priceSale');
    $data['price_total'] = $request->input('priceTotal');

    $response = $this->validateData($data, null);
    if (!$response['status']) return json_encode($response);

    try {
      Material::create($data);

      $response = [
        'status' => true,
        'message' => 'Registro correcto.',
      ];
    } catch (\Exception $e) {
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
    $material = Material::find($id);
    $categories = Category::all();
    $units = Unit::all();
    $pageTitle = 'Editar Material';

    return view('pages.materials.edit', compact('material', 'categories', 'units', 'pageTitle'));
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
    $data['category_id'] = $request->input('category');
    $data['unit_id'] = $request->input('unit');
    $data['type'] = $request->input('type');
    $data['code'] = mb_strtoupper($request->input('code'));
    $data['name'] = mb_strtoupper($request->input('name'));
    $data['brand'] = mb_strtoupper($request->input('brand'));
    $data['price'] = $request->input('price');
    $data['price_sale'] = $request->input('priceSale');
    $data['price_total'] = $request->input('priceTotal');

    $response = $this->validateData($data, $id);
    if (!$response['status']) return json_encode($response);

    try {
      $material = Material::find($id);
      $material->update($data);
      $response = ['status' => true, 'message' => 'Actualización correcta.'];
    } catch (\Exception $e) {
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
      $category = Material::findOrFail($id);
      $category->delete();

      $response = ['status' => true, 'message' => 'Eliminación correcta.'];
    } catch (\Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }
    return json_encode($response);
  }

  /**
   * Get stock by material id
   *
   * @return json
   */
  public function stock(Request $request)
  {
    $idMaterial = $request->input('idMaterial');
    $query = Stock::from('stock as s')
      ->join('materials as m', 's.material_id', 'm.id')
      ->select(
        's.id',
        's.material_id',
        's.batch',
        's.amount',
        's.expiration_date',
        'm.name as material_name',
      )
      ->where('s.material_id', $idMaterial)
      ->orderBy('s.id', 'desc');

    return datatables()->eloquent($query)->toJson();
  }

  /**
   * Register an entry for stock
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function addStock(Request $request)
  {
    $data = array();
    $data['material_id'] = $request->input('idMaterial');
    $data['batch'] = mb_strtoupper($request->input('batch'));
    $data['amount'] = $request->input('amount');
    $data['expiration_date'] = $request->input('expirationDate');

    try {
      Stock::create($data);

      $response = [
        'status' => true,
        'message' => 'Registro correcto.',
      ];
    } catch (\Exception $e) {
      $response = ['status' => false, 'message' => $e->getMessage()];
    }

    return json_encode($response);
  }
}
