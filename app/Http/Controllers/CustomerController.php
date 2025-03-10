<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Customer;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    protected $customerService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Clientes';
        $route = 'customers';

        return view('pages.customers.index', compact('pageTitle', 'route'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $query = Customer::select(
            'id',
            'document_number',
            'full_name',
            'credit_limit',
            'available_balance',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
            DB::raw("DATE_FORMAT(updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted")
        )
            ->where('active', 1)
            ->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.customers.columns.actions')
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
        $pageTitle = 'Nuevo Cliente';

        return view('pages.customers.create', compact('pageTitle'));
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
        $data['full_name'] = mb_strtoupper($request->input('fullName'));
        $data['credit_limit'] = $request->input('creditLimit');

        try {
            Customer::create($data);

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
        $customer = Customer::find($id);
        $pageTitle = 'Editar Cliente';

        return view('pages.customers.edit', compact('customer', 'pageTitle'));
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
        $data['full_name'] = mb_strtoupper($request->input('fullName'));
        $data['credit_limit'] = $request->input('creditLimit');

        try {
            $customer = Customer::find($id);
            $customer->update($data);
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
            $customer = Customer::findOrFail($id);
            $customer->active = 0;
            $customer->save();

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
            $customer = $this->customerService->findByDocumentNumber($documentNumber);

            $response = ['status' => true, 'data' => $customer];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    /**
     * Get data to show in datatables for transactions (sales)
     *
     * @return json
     */
    public function getCustomersForTransactions()
    {
        $query = Customer::select(
            'id',
            'document_number',
            'full_name'
        )
            ->where('active', true)
            ->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-select', 'pages.customers.columns.select')
            ->rawColumns(['col-select'])
            ->toJson();
    }
}
