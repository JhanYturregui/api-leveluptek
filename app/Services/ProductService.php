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

    public function getFavorites()
    {
        return Product::where('favorite', 1)->with('prices')->get();
    }

    public function getByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)->where('active', 1)->with('prices')->get();
    }
}
