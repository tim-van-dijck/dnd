<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('subclass_id')->nullable();
            $table->unsignedTinyInteger('level');
            $table->unsignedTinyInteger('cantrips_known');
            $table->unsignedTinyInteger('spells_known');
            $table->unsignedTinyInteger('spell_slots_level_1');
            $table->unsignedTinyInteger('spell_slots_level_2');
            $table->unsignedTinyInteger('spell_slots_level_3');
            $table->unsignedTinyInteger('spell_slots_level_4');
            $table->unsignedTinyInteger('spell_slots_level_5');
            $table->unsignedTinyInteger('spell_slots_level_6');
            $table->unsignedTinyInteger('spell_slots_level_7');
            $table->unsignedTinyInteger('spell_slots_level_8');
            $table->unsignedTinyInteger('spell_slots_level_9');
            $table->json('class_specific');

            $table->foreign('class_id')->references('id')->on('classes')
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
        Schema::dropIfExists('class_levels');
    }
}
