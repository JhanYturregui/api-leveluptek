<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'category_id', 'code', 'name', 'stock', 'favorite', 'has_container', 'active'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'product_purchases')->withPivot('quantity', 'price');
    }
}
