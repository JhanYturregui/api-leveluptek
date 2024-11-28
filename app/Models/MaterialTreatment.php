<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTreatment extends Model
{
  use HasFactory;

  protected $table = 'materials_treatments';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['material_id', 'treatment_id', 'amount'];
}
