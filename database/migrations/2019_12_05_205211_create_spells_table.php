<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spells', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('range');
            $table->string('components');
            $table->string('materials')->nullable();
            $table->boolean('ritual');
            $table->boolean('concentration');
            $table->string('duration');
            $table->string('casting_time');
            $table->integer('level');
            $table->string('school');
            $table->text('description');
            $table->text('higher_levels')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spells');
    }
}
