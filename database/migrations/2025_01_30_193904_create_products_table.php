<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId(('category_id'))->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code', 30);
            $table->string('name');
            $table->enum('unit', [config('constants.MEASURE_UNIT_UNIT')])->default(config(('constants.MEASURE_UNIT_UNIT')));
            $table->boolean('favorite')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
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
