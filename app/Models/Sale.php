<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['cash_session_id', 'customer_id', 'correlative', 'total_amount', 'amount_payed', 'type', 'payment_method', 'canceled'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sales')->withPivot('quantity', 'price');
    }
}
