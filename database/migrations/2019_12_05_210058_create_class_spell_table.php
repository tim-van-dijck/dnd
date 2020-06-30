<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassSpellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_spell', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('class_id', false, true);
            $table->integer('spell_id', false, true);

            $table->foreign('class_id')->references('id')->on('classes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('spell_id')->references('id')->on('spells')
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
        Schema::dropIfExists('class_spell');
    }
}
