<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['category_id', 'unit_id', 'type', 'code', 'name', 'brand', 'price', 'price_sale', 'price_total'];

  public function treatments()
  {
    return $this->belongsToMany(Treatment::class, 'materials_treatments')->withPivot('amount');
  }
}
