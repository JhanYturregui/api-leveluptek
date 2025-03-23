<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function findOne($id)
    {
        return Product::find($id);
    }

    public function increaseStock($id, $quantity)
    {
        $product = $this->findOne($id);
        $product->stock += $quantity;
        $product->save();
    }

    public function decreaseStock($id, $quantity)
    {
        $product = $this->findOne($id);
        $product->stock -= $quantity;
        $product->save();
    }

    public function getByCategory($categoryId)
    {
        $products = Product::where('active', 1)
            ->when(intval($categoryId) === 0, function ($query) {
                return $query->where('favorite', 1);
            })
            ->when(intval($categoryId) !== 0, function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->where('code', '!=', config('constants.CODE_PRODUCT_CONTAINER'))
            ->with('prices')
            ->get();

        foreach ($products as $product) {
            $arrSalesPrices = [];
            foreach ($product['prices'] as $price) {
                array_push($arrSalesPrices, $price->price);
            }
            $product->sales_prices = $arrSalesPrices;
        }

        return $products;
    }

    public function findByCode($code)
    {
        $product = Product::where('code', $code)->where('active', 1)->where('parent_id', null)->with('prices')->first();
        if ($product) {
            $prices = $product['prices'];
            $arrSalesPrices = [];
            foreach ($prices as $price) {
                array_push($arrSalesPrices, $price->price);
            }
            $product->sales_prices = $arrSalesPrices;
        }

        return $product;
    }
}
