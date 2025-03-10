<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['document_number', 'full_name', 'credit_limit', 'available_balance', 'active'];

    protected $append = ['debt'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function cash_transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function getDebtAttribute()
    {
        return $this->credit_limit - $this->available_balance;
    }
}
