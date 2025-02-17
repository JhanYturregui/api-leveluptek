<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films_vehicles', function (Blueprint $table) {
            $table->string('film_url');
            $table->string('vehicle_url');
            $table->foreign('film_url')->references('url')->on('films')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('vehicle_url')->references('url')->on('vehicles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['film_url', 'vehicle_url']);
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
        Schema::dropIfExists('films_vehicles');
    }
}
