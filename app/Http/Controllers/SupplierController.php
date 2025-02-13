<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;

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
