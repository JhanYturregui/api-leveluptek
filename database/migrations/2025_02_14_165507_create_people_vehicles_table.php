<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_vehicles', function (Blueprint $table) {
            $table->string('people_url');
            $table->string('vehicle_url');
            $table->foreign('people_url')->references('url')->on('people')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('vehicle_url')->references('url')->on('vehicles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['people_url', 'vehicle_url']);
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
        Schema::dropIfExists('people_vehicles');
    }
}
