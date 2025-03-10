<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['cash_session_id', 'supplier_id', 'correlative', 'total_amount', 'url_image', 'canceled'];

    public function cash_session()
    {
        return $this->belongsTo(CashSession::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_purchases')->withPivot('quantity', 'price');
    }
}
