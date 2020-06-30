<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProficiencyRaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proficiency_race', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('race_id', false, true);
            $table->integer('proficiency_id', false, true);

            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('proficiency_id')->references('id')->on('proficiencies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proficiency_race');
    }
}
