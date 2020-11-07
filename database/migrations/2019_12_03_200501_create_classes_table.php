<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('subclass_flavor');
            $table->integer('hit_die');
            $table->boolean('spellcaster')->default(false);
            $table->integer('instrument_choices')->default(0);
            $table->integer('skill_choices')->default(0);
            $table->integer('tool_choices')->default(0);
            $table->json('saving_throws');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
