<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\CashSession;
use App\Models\Sale;
use App\Services\CategoryService;
use App\Services\ProductService;

class SaleController extends Controller
{
    protected $productService;
    protected $categoryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->middleware('auth');
        date_default_timezone_set('America/Lima');
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

        $cashSession = CashSession::getOpenCashSessionByUserId(auth()->user()->id);
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
        $cashSession = CashSession::getOpenCashSessionByUserId(auth()->user()->id);
        $query = Sale::from('sales as s')
            ->select(
                's.id',
                's.total_amount',
                DB::raw("DATE_FORMAT(s.created_at, '%d-%m-%Y %H:%i:%s') as created_at_formatted"),
                'c.full_name'
            )
            ->join('customers as c', 's.customer_id', 'c.id')
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
        $favorites = $this->productService->getFavorites();

        return view('pages.sales.create', compact('pageTitle', 'categories', 'favorites'));
    }
}
