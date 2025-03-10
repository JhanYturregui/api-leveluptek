<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'correlative', 'date', 'opening_amount', 'closing_amount', 'real_closing_amount', 'comment', 'open'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function cash_transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public static function getOpenCashSessionByUserId($userId)
    {
        return self::where('user_id', $userId)->where('date', date('Y-m-d'))->where('open', true)->orderBy('id', 'desc')->first();
    }

    public static function getOpenCashSessionByRole()
    {
        if (auth()->user()->role === config('constants.USER_ROLE_SELLER')) {
            return self::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->where('open', true)->orderBy('id', 'desc')->first();
        } else {
            return self::where('date', date('Y-m-d'))->where('open', true)->orderBy('id', 'desc')->first();
        }
    }
}
