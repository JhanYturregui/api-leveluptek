<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

class ProductController extends Controller
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
}
