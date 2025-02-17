<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleSpeciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_species', function (Blueprint $table) {
            $table->string('people_url');
            $table->string('specie_url');
            $table->foreign('people_url')->references('url')->on('people')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('specie_url')->references('url')->on('species')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['people_url', 'specie_url']);
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
        Schema::dropIfExists('people_species');
    }
}
