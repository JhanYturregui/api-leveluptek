<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
