<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stock', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('material_id');
      $table->string('batch', 30)->nullable();
      $table->decimal('amount', 10, 2)->default(0);
      $table->date('expiration_date');
      $table->timestamps();
      $table->foreign('material_id')->references('id')->on('materials')->onUpdate('cascade')->onDelete('restrict');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('stock');
  }
}
