<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['document_number', 'full_name', 'credit_limit', 'active'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
