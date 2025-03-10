<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleService
{
    public function findOne($id)
    {
        return Sale::with(['products.prices'])->find($id);
    }
}
