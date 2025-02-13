<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseService
{
    public function findOne($id)
    {
        return Purchase::find($id);
    }
}
