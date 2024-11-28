<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'price'];

  public function materials()
  {
    return $this->belongsToMany(Material::class, 'materials_treatments')->withPivot('amount');
  }
}
