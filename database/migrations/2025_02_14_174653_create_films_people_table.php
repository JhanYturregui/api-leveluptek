<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films_people', function (Blueprint $table) {
            $table->string('film_url');
            $table->string('people_url');
            $table->foreign('film_url')->references('url')->on('films')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('people_url')->references('url')->on('people')->cascadeOnUpdate()->cascadeOnDelete();
            $table->primary(['film_url', 'people_url']);
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
        Schema::dropIfExists('films_people');
    }
}
