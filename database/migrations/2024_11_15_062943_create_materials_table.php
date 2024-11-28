<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('materials', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('category_id');
      $table->unsignedBigInteger('unit_id');
      $table->enum('type', [config('constants.PRODUCT_TYPE_WHOLE'), config('constants.PRODUCT_TYPE_FRACTIONAL')])->default(config('constants.PRODUCT_TYPE_WHOLE'));
      $table->string('code', 30);
      $table->string('name');
      $table->string('brand');
      $table->decimal('price', 10, 2);
      $table->decimal('price_sale', 10, 2);
      $table->decimal('price_total', 10, 2);
      $table->timestamps();
      $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('restrict');
      $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('restrict');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('products');
  }
}
