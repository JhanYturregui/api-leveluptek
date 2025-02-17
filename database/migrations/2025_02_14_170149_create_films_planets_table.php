<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsPlanetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films_planets', function (Blueprint $table) {
            $table->string('film_url');
            $table->string('planet_url');
            $table->foreign('film_url')->references('url')->on('films')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('planet_url')->references('url')->on('planets')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['film_url', 'planet_url']);
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
        Schema::dropIfExists('films_planets');
    }
}
