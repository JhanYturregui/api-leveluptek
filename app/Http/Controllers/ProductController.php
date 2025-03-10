<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Productos';
        $route = 'products';

        return view('pages.products.index', compact('pageTitle', 'route'));
    }

    /**
     * Get data to show in datatables
     *
     * @return json
     */
    public function getData()
    {
        $query = Product::from('products as p')
            ->select(
                'p.id',
                'p.code',
                'p.name',
                'c.name as category_name'
            )
            ->join('categories as c', 'p.category_id', 'c.id')
            ->with('prices')
            ->where('p.active', true)
            ->orderBy('p.id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('prices', function ($query) {
                return $query->prices->pluck('price')->implode(', ');
            })
            ->addColumn('col-actions', 'pages.products.columns.actions')
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
        $pageTitle = 'Nuevo Producto';
        $categories = Category::where('active', true)->get();

        return view('pages.products.create', compact('pageTitle', 'categories'));
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
        $data['code'] = mb_strtoupper($request->input('code'));
        $data['category_id'] = $request->input('category');
        $data['favorite'] = ($request->input('favorite') === 'true');
        $data['favorite'] = $request->input('favorite');
        $prices = $request->input('pricesList');

        DB::beginTransaction();
        try {
            $product = Product::create($data);

            if ($prices) {
                $pricesList = [];
                foreach ($prices as $price) {
                    $pricesList[] = ['price' => $price];
                }
                $product->prices()->createMany($pricesList);
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
        $pageTitle = 'Editar Producto';
        $product = Product::with('prices')->find($id);
        $categories = Category::where('active', true)->get();

        return view('pages.products.edit', compact('pageTitle', 'product', 'categories'));
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
        $data['code'] = mb_strtoupper($request->input('code'));
        $data['name'] = mb_strtoupper($request->input('name'));
        $data['category_id'] = $request->input('category');
        $data['favorite'] = ($request->input('favorite') === 'true');
        $prices = $request->input('pricesList');

        DB::beginTransaction();
        try {
            $product = Product::find($id);
            $product->update($data);

            Price::where('product_id', $id)->delete();

            if ($prices) {
                $pricesList = [];
                foreach ($prices as $price) {
                    $pricesList[] = ['price' => $price];
                }
                $product->prices()->createMany($pricesList);
            }
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

        try {
            $product = Product::findOrFail($id);
            $product->update(['active' => false]);

            $response = ['status' => true, 'message' => 'Eliminación correcta.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    /**
     * Find resource by code
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function findByCode(Request $request)
    {
        $code = mb_strtoupper($request->input('code'));
        $response = array();

        try {
            $product = $this->productService->findByCode($code);

            $response = ['status' => true, 'data' => $product];
        } catch (\Exception $e) {
            $response = ['status' => false, 'message' => $e->getMessage()];
        }
        return json_encode($response);
    }

    /**
     * Get data to show in datatables for transactions (purchases or sales)
     *
     * @return json
     */
    public function getProductsForTransactions(Request $request)
    {
        $isSale = $request->input('isSale');
        $view = $isSale === 'true' ? 'pages.products.columns.select-for-sale' : 'pages.products.columns.select-for-purchase';
        $query = Product::from('products as p')
            ->select(
                'p.id',
                'p.code',
                'p.name',
                'p.stock',
                'c.name as category_name'
            )
            ->join('categories as c', 'p.category_id', 'c.id')
            ->with('prices')
            ->where('p.active', true)
            ->orderBy('p.id', 'desc');

        return datatables()
            ->eloquent($query)
            ->addColumn('sales_prices', function ($query) {
                return $query->prices->pluck('price');
            })
            ->addColumn('col-select', $view)
            ->rawColumns(['col-select'])
            ->toJson();
    }

    /**
     * Get data to show in datatables for transactions (purchases or sales)
     *
     * @return json
     */
    public function getByCategory($categoryId)
    {
        $products = $this->productService->getByCategory($categoryId);
        $response = ['status' => true, 'data' => $products];

        return json_encode($response);
    }
}
