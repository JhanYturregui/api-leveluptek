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

    public static function getOpenCashSessionByUserId($userId)
    {
        return self::where('user_id', $userId)->where('date', date('Y-m-d'))->where('open', true)->first();
    }
}
