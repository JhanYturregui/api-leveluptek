<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CashSession;
use App\Models\Product;
use App\Models\Purchase;
use App\Services\PurchaseService;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    protected $purchaseService;
    protected $productService;
    protected $categoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService, CategoryService $categoryService, PurchaseService $purchaseService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
        $this->purchaseService = $purchaseService;
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
        $pageTitle = 'Compras';
        $route = 'purchases';

        $cashSession = CashSession::getOpenCashSessionByRole();
        $cashSessionId = $cashSession ? $cashSession->id : 0;

        return view('pages.purchases.index', compact('pageTitle', 'route', 'cashSessionId'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $cashSession = CashSession::getOpenCashSessionByRole();
        $query = Purchase::from('purchases as p')
            ->select(
                'p.id',
                'p.total_amount',
                DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
                's.business_name'
            )
            ->join('suppliers as s', 'p.supplier_id', 's.id')
            ->where('p.cash_session_id', $cashSession->id)
            ->orderBy('p.id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('col-actions', 'pages.purchases.columns.actions')
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
        $pageTitle = 'Nueva Compra';
        $categories = $this->categoryService->getAll();
        $favorites = $this->productService->getByCategory(0);

        return view('pages.purchases.create', compact('pageTitle', 'categories', 'favorites'));
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
            $response = ['status' => false, 'message' => 'Error al registrar la compra'];
            return json_encode($response);
        }

        $data = array();
        $data['cash_session_id'] = $cashSession->id;
        $data['supplier_id'] = $request->input('supplierId');
        $data['total_amount'] = $request->input('totalAmount');
        $products = json_decode($request->input('productsList'));

        DB::beginTransaction();
        try {
            $purchase = Purchase::create($data);

            if ($request->hasFile('invoice')) {
                $imagePath = $request->file('invoice')->store('images/purchases/' . $purchase->id, 'public');
                $purchase->url_image = $imagePath;
                $purchase->save();
            }

            $productsList = [];
            foreach ($products as $product) {
                $this->productService->increaseStock($product->id, $product->quantity);
                $productsList[$product->id] = ['quantity' => $product->quantity, 'price' => $product->price];
                if (intval($product->hasContainer) === 1) {
                    $productContainerId = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
                    $this->productService->increaseStock($productContainerId, $product->quantity);
                }
            }
            $purchase->products()->attach($productsList);
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
        $purchase = $this->purchaseService->findOne($id);
        $purchase->path_image = $purchase->url_image ? asset('storage/' . $purchase->url_image) : '';
        $pageTitle = 'Editar Compra';
        $categories = $this->categoryService->getAll();
        $favorites = $this->productService->getByCategory(0);

        return view('pages.purchases.edit', compact('purchase', 'pageTitle', 'categories', 'favorites'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = intval($request->input('purchaseId'));
        $cashSession = CashSession::getOpenCashSessionByUserId(auth()->user()->id);
        if (!$cashSession) {
            $response = ['status' => false, 'message' => 'Error al actualizar la compra'];
            return json_encode($response);
        }

        $data = array();
        $data['cash_session_id'] = $cashSession->id;
        $data['supplier_id'] = $request->input('supplierId');
        $data['total_amount'] = $request->input('totalAmount');
        $products = json_decode($request->input('productsList'));

        DB::beginTransaction();
        try {
            $purchase = $this->purchaseService->findOne($id);
            $purchase->update($data);

            if ($request->hasFile('invoice')) {
                if ($purchase->url_image !== null) {
                    Storage::delete('public/' . $purchase->url_image);
                }
                $imagePath = $request->file('invoice')->store('images/purchases/' . $purchase->id, 'public');
                $purchase->url_image = $imagePath;
                $purchase->save();
            }

            $oldProductsList = $purchase->products;
            foreach ($oldProductsList as $product) {
                $this->productService->decreaseStock($product->id, $product->pivot->quantity);
                if (intval($product->has_container) === 1) {
                    $productContainerId = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
                    $this->productService->decreaseStock($productContainerId, $product->pivot->quantity);
                }
            }

            $productsList = [];
            foreach ($products as $product) {
                $this->productService->increaseStock($product->id, $product->quantity);
                $productsList[$product->id] = ['quantity' => $product->quantity, 'price' => $product->price];
                if (intval($product->hasContainer) === 1) {
                    $productContainerId = Product::where('code', config('constants.CODE_PRODUCT_CONTAINER'))->value('id');
                    $this->productService->increaseStock($productContainerId, $product->quantity);
                }
            }
            $purchase->products()->sync($productsList);
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
            $purchase = $this->purchaseService->findOne($id);

            foreach ($purchase->products as $product) {
                $this->productService->decreaseStock($product->id, $product->pivot->quantity);
            }

            $purchase->delete();

            DB::commit();
            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }
}
