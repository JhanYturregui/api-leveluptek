<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
  use HasFactory;

  protected $table = 'stock';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['material_id', 'batch', 'amount', 'expiration_date'];
}
