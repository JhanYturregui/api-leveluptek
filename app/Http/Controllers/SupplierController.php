<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Supplier;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    protected $supplierService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
        $this->supplierService = $supplierService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Proveedores';
        $route = 'suppliers';

        return view('pages.suppliers.index', compact('pageTitle', 'route'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $query = Supplier::select(
            'id',
            'document_number',
            'business_name',
            'phone',
            'address',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
            DB::raw("DATE_FORMAT(updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted")
        )
            ->where('active', 1)
            ->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.suppliers.columns.actions')
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
        $pageTitle = 'Nuevo Proveedor';

        return view('pages.suppliers.create', compact('pageTitle'));
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
        $data['document_number'] = $request->input('documentNumber');
        $data['business_name'] = mb_strtoupper($request->input('businessName'));
        $data['phone'] = $request->input('phone');
        $data['address'] = mb_strtoupper($request->input('address'));

        try {
            Supplier::create($data);

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
        $supplier = Supplier::find($id);
        $pageTitle = 'Editar Proveedor';

        return view('pages.suppliers.edit', compact('supplier', 'pageTitle'));
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
        $data['document_number'] = $request->input('documentNumber');
        $data['business_name'] = mb_strtoupper($request->input('businessName'));
        $data['phone'] = $request->input('phone');
        $data['address'] = mb_strtoupper($request->input('address'));

        try {
            $supplier = Supplier::find($id);
            $supplier->update($data);
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
            $supplier = Supplier::findOrFail($id);
            $supplier->active = 0;
            $supplier->save();

            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    /**
     * Find resource by document number
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function findByDocumentNumber(Request $request)
    {
        $documentNumber = $request->input('documentNumber');
        $response = array();

        try {
            $supplier = $this->supplierService->findByDocumentNumber($documentNumber);

            $response = ['status' => true, 'data' => $supplier];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    /**
     * Get data to show in datatables for transactions (purchases)
     *
     * @return json
     */
    public function getSuppliersForTransactions()
    {
        $query = Supplier::select(
            'id',
            'document_number',
            'business_name',
            'phone',
            'address'
        )
            ->where('active', true)
            ->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-select', 'pages.suppliers.columns.select')
            ->rawColumns(['col-select'])
            ->toJson();
    }
}
