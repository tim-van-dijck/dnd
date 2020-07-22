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
            $table->unsignedInteger('race_id');
            $table->unsignedInteger('subrace_id')->nullable();
            $table->unsignedInteger('proficiency_id');
            $table->boolean('optional')->default(false);

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
