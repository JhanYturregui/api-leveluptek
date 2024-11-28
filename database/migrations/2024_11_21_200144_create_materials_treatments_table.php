<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTreatmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('materials_treatments', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('treatment_id');
      $table->unsignedBigInteger('material_id');
      $table->decimal('amount', 10, 2);
      $table->timestamps();
      $table->foreign('treatment_id')->references('id')->on('treatments')->onUpdate('cascade')->onDelete('cascade');
      $table->foreign('material_id')->references('id')->on('materials')->onUpdate('cascade')->onDelete('cascade');
      $table->unique(['treatment_id', 'material_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('materials_treatments');
  }
}
