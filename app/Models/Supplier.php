<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['document_number', 'business_name', 'phone', 'address', 'active'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
