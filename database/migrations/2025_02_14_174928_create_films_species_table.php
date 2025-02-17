<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsSpeciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films_species', function (Blueprint $table) {
            $table->string('film_url');
            $table->string('specie_url');
            $table->foreign('film_url')->references('url')->on('films')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('specie_url')->references('url')->on('species')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['film_url', 'specie_url']);
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
        Schema::dropIfExists('films_species');
    }
}
