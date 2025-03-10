<?php

namespace App\Http\Controllers;

use App\Models\CashSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CashTransaction;
use App\Models\Customer;
use App\Services\CustomerService;

class CashTransactionController extends Controller
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
        $pageTitle = 'Movimientos de Caja';
        $route = 'cash_transactions';

        $cashSession = CashSession::getOpenCashSessionByRole();
        $cashSessionId = $cashSession ? $cashSession->id : 0;

        return view('pages.cash_transactions.index', compact('pageTitle', 'route', 'cashSessionId'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $cashSession = CashSession::getOpenCashSessionByRole();
        $query = CashTransaction::select(
            'id',
            'type',
            'description',
            'amount',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
            DB::raw("DATE_FORMAT(updated_at, '%d-%m-%Y %H:%i:%s') as updated_at_formatted")
        )
            ->where('cash_session_id', $cashSession->id)
            ->orderBy('id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.cash_transactions.columns.actions')
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
        $pageTitle = 'Nuevo Movimiento de Caja';

        return view('pages.cash_transactions.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cashSession = CashSession::getOpenCashSessionByUserId(auth()->user()->id);
        $type = mb_strtolower($request->input('type'));
        $amount = $request->input('amount');
        $customerId = $request->input('customerId');
        $description = mb_strtoupper($request->input('description'));

        $data = array();
        $data['cash_session_id'] = $cashSession->id;
        $data['type'] = $type;
        $data['amount'] = $amount;
        $data['description'] = $description;

        DB::beginTransaction();
        try {
            if ($type === config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_PAY')) {
                $response = $this->customerService->verifyDebt($customerId, $amount, null);
                if (!$response['status']) {
                    return json_encode($response);
                }
                $this->customerService->updateAvailableBalance($customerId, $amount, true);
                $data['customer_id'] = $customerId;
            }
            CashTransaction::create($data);

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
        $cashTransaction = CashTransaction::find($id);
        $pageTitle = 'Editar Movimiento de Caja';

        return view('pages.cash_transactions.edit', compact('cashTransaction', 'pageTitle'));
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
        $amount = $request->input('amount');
        $description = mb_strtoupper($request->input('description'));

        $data = array();
        $data['amount'] = $amount;
        $data['description'] = $description;

        DB::beginTransaction();
        try {
            $cashTransaction = CashTransaction::find($id);
            if ($cashTransaction->type === config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_PAY')) {
                $response = $this->customerService->verifyDebt($cashTransaction->customer_id, $amount, $cashTransaction->amount);
                if (!$response['status']) {
                    return json_encode($response);
                }
                $this->customerService->updateAvailableBalance($cashTransaction->customer_id, ($amount - $cashTransaction->amount), true);
            }

            $cashTransaction->update($data);
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

        DB::beginTransaction();
        try {
            $cashTransaction = CashTransaction::findOrFail($id);
            if ($cashTransaction->type === config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_PAY')) {
                $customer = $this->customerService->findById($cashTransaction->customer_id);
                if ($customer->available_balance < $cashTransaction->amount) {
                    $response['status'] = false;
                    $response['message'] = 'Error al eliminar movimiento. Límite de crédito excedido';
                    return json_encode($response);
                }
                $this->customerService->updateAvailableBalance($cashTransaction->customer_id, $cashTransaction->amount, false);
            }
            $cashTransaction->delete();
            DB::commit();

            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
