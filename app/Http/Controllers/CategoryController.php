<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Category;

class CategoryController extends Controller
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
        $pageTitle = 'Categorías';
        $route = 'categories';

        return view('pages.categories.index', compact('pageTitle', 'route'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $query = Category::select(
            'id',
            'name',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
            DB::raw("DATE_FORMAT(updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted")
        )->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.categories.columns.actions')
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
    public function validateData($data, $id)
    {
        $response = array();

        if ($id) {
            if (!Category::find($id)) {
                $response = ['status' => false, 'mensaje' => 'El registro no existe.'];
                return $response;
            }
        }

        $unique = $id ? 'unique:categories,name,' . $id : 'unique:categories';
        $rules = ['name' => 'bail|required|' . $unique];

        $messages = [
            'name.required'  => 'El campo NOMBRE es obligatorio.',
            'name.unique'    => 'El NOMBRE ya está registrado.',
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Nueva Categoría';

        return view('pages.categories.create', compact('pageTitle'));
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

        $response = $this->validateData($data, null);
        if (!$response['status']) return json_encode($response);

        try {
            Category::create($data);

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
        $category = Category::find($id);
        $pageTitle = 'Editar Categoría';

        return view('pages.categories.edit', compact('category', 'pageTitle'));
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

        $response = $this->validateData($data, $id);
        if (!$response['status']) return json_encode($response);

        try {
            $category = Category::find($id);
            $category->update($data);
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
            $category = Category::findOrFail($id);
            $category->delete();

            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
