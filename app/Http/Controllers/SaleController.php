<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CashSession;
use App\Models\CashTransaction;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\SaleService;

class SaleController extends Controller
{
    protected $saleService;
    protected $productService;
    protected $categoryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SaleService $saleService, ProductService $productService, CategoryService $categoryService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
        $this->saleService = $saleService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Ventas';
        $route = 'sales';

        $cashSession = CashSession::getOpenCashSessionByRole();
        $cashSessionId = $cashSession ? $cashSession->id : 0;

        return view('pages.sales.index', compact('pageTitle', 'route', 'cashSessionId'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $cashSession = CashSession::getOpenCashSessionByRole();
        $query = Sale::from('sales as s')
            ->select(
                's.id',
                's.total_amount',
                DB::raw("DATE_FORMAT(s.created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
                'c.full_name as customer_fullname'
            )
            ->leftJoin('customers as c', 's.customer_id', 'c.id')
            ->where('s.cash_session_id', $cashSession->id)
            ->orderBy('s.id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.sales.columns.actions')
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
        $pageTitle = 'Nueva Venta';
        $categories = $this->categoryService->getAll();
        $favorites = $this->productService->getByCategory(0);

        return view('pages.sales.create', compact('pageTitle', 'categories', 'favorites'));
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
        if (!$cashSession) {
            $response = ['status' => false, 'message' => 'Error al registrar la venta'];
            return json_encode($response);
        }

        $saleType = $request->input('saleType');
        $customerId = $request->input('customerId');
        $totalAmount = $request->input('totalAmount');
        $partialPayment = $request->input('partialPayment');
        if ($saleType === config('constants.SALE_TYPES.SALE_TYPE_CREDIT')) {
            $customer = Customer::find($customerId);
            if ($customer->available_balance < ($totalAmount - $partialPayment)) {
                $response = ['status' => false, 'message' => 'Cliente con saldo insuficiente. Saldo disponible: ' . $customer->available_balance];
                return json_encode($response);
            }
        }

        $data = array();
        $data['cash_session_id'] = $cashSession->id;
        $data['customer_id'] = $customerId;
        $data['total_amount'] = $totalAmount;
        $data['partial_payment'] = $saleType === config('constants.SALE_TYPES.SALE_TYPE_CREDIT') ? $partialPayment : $totalAmount;
        $data['type'] = $saleType;
        $data['payment_method'] = $request->input('paymentMethod');
        $data['bring_container'] = $request->input('bringContainer');
        $data['total_count_containers'] = $request->input('totalCountContainers');
        $products = json_decode($request->input('productsList'));

        DB::beginTransaction();
        try {
            $sale = Sale::create($data);

            $productsList = [];
            foreach ($products as $product) {
                $this->productService->decreaseStock($product->id, $product->quantity);
                $productsList[$product->id] = ['quantity' => $product->quantity, 'price' => $product->price];
            }
            $sale->products()->attach($productsList);

            $totalCredit = $totalAmount - $partialPayment;
            if ($request->input('saleWithContainers')) {
                if (!$request->input('bringContainer')) {
                    $idProductWithContainer = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
                    $this->productService->decreaseStock($idProductWithContainer, $request->input('totalCountContainers'));

                    $dataCashTransaction = array();
                    $dataCashTransaction['cash_session_id'] = $cashSession->id;
                    $dataCashTransaction['customer_id'] = $customerId;
                    $dataCashTransaction['type'] = config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_INCOME');
                    //$dataCashTransaction['description'] = '';
                    $dataCashTransaction['amount'] = $request->input('totalContainers');
                    $dataCashTransaction['sale_id'] = $sale->id;
                    CashTransaction::create($dataCashTransaction);

                    $totalCredit += $request->input('totalContainers');
                }
            }

            if ($saleType === config('constants.SALE_TYPES.SALE_TYPE_CREDIT')) {
                $customer = Customer::find($customerId);
                $customer->available_balance -= $totalCredit;
                $customer->save();
            }

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
        $sale = $this->saleService->findOne($id);
        $pageTitle = 'Editar Venta';
        $categories = $this->categoryService->getAll();
        $favorites = $this->productService->getByCategory(0);

        return view('pages.sales.edit', compact('sale', 'pageTitle', 'categories', 'favorites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = intval($request->input('saleId'));
        $cashSession = CashSession::getOpenCashSessionByUserId(auth()->user()->id);
        if (!$cashSession) {
            $response = ['status' => false, 'message' => 'Error al actualizar la venta'];
            return json_encode($response);
        }

        $sale = $this->saleService->findOne($id);

        //$saleType = $request->input('saleType');
        $customerId = $request->input('customerId');
        $totalAmount = $request->input('totalAmount');
        $partialPayment = $request->input('partialPayment');
        if ($sale->type === config('constants.SALE_TYPES.SALE_TYPE_CREDIT')) {
            $customer = Customer::find($customerId);
            $oldDebt = $sale->total_amount - $sale->partial_payment;
            $available = $customer->available_balance + $oldDebt;
            if ($available < ($totalAmount - $partialPayment)) {
                $response = ['status' => false, 'message' => 'Cliente con saldo insuficiente. Saldo disponible: ' . $available];
                return json_encode($response);
            }
        }

        $idProductWithContainer = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
        if (!$sale->bring_container) {
            $this->productService->increaseStock($idProductWithContainer, $sale->total_count_containers);
        }

        $oldProducts = $sale->products;
        foreach ($oldProducts as $product) {
            $this->productService->increaseStock($product->id, $product->pivot->quantity);
        }

        $data = array();
        $data['cash_session_id'] = $cashSession->id;
        $data['customer_id'] = $customerId;
        $data['total_amount'] = $totalAmount;
        $data['partial_payment'] = $sale->type === config('constants.SALE_TYPES.SALE_TYPE_CREDIT') ? $partialPayment : $totalAmount;
        //$data['type'] = $saleType;
        $data['payment_method'] = $request->input('paymentMethod');
        $data['bring_container'] = $request->input('bringContainer');
        $data['total_count_containers'] = $request->input('totalCountContainers');
        $products = json_decode($request->input('productsList'));

        DB::beginTransaction();
        try {
            $sale = $this->saleService->findOne($id);
            $sale->update($data);

            $productsList = [];
            foreach ($products as $product) {
                $this->productService->decreaseStock($product->id, $product->quantity);
                $productsList[$product->id] = ['quantity' => $product->quantity, 'price' => $product->price];
            }
            $sale->products()->sync($productsList);

            if ($request->input('saleWithContainers')) {
                if (!$request->input('bringContainer')) {
                    $this->productService->decreaseStock($idProductWithContainer, $request->input('totalCountContainers'));

                    $dataCashTransaction = array();
                    $dataCashTransaction['cash_session_id'] = $cashSession->id;
                    $dataCashTransaction['customer_id'] = $customerId;
                    $dataCashTransaction['type'] = config('constants.CASH_TRANSACTION_TYPES.CASH_TRANSACTION_INCOME');
                    //$dataCashTransaction['description'] = '';
                    $dataCashTransaction['amount'] = $request->input('totalContainers');
                    $dataCashTransaction['sale_id'] = $sale->id;
                    CashTransaction::create($dataCashTransaction);
                } else {
                    $cashTransaction = CashTransaction::where('sale_id', $id)->first();
                    if ($cashTransaction) {
                        $cashTransaction->delete();
                    }
                }
            }

            if ($sale->type === config('constants.SALE_TYPES.SALE_TYPE_CREDIT')) {
                $customer = Customer::find($customerId);
                $customer->available_balance = $customer->available_balance - $totalAmount + $partialPayment + $oldDebt;
                $customer->save();
            }

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
            $sale = $this->saleService->findOne($id);

            foreach ($sale->products as $product) {
                $this->productService->increaseStock($product->id, $product->pivot->quantity);
            }

            if (!$sale->bring_container) {
                $idProductWithContainer = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
                $this->productService->increaseStock($idProductWithContainer, $sale->total_count_containers);
                $cashTransaction = CashTransaction::where('sale_id', $id)->first();
                if ($cashTransaction) {
                    $cashTransaction->delete();
                }
            }

            if ($sale->type === config('constants.SALE_TYPES.SALE_TYPE_CREDIT')) {
                $customer = Customer::find($sale->customer_id);
                $customer->available_balance += $sale->total_amount - $sale->partial_payment;
                $customer->save();
            }

            $sale->delete();

            DB::commit();
            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
