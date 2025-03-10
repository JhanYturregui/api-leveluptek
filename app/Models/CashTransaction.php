<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['cash_session_id', 'customer_id', 'type', 'description', 'amount'];

    public function cash_session()
    {
        return $this->belongsTo(CashSession::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
