<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpellSubclassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spell_subclass', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subclass_id', false, true);
            $table->integer('spell_id', false, true);

            $table->foreign('subclass_id')->references('id')->on('subclasses')
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
        Schema::dropIfExists('spell_subclass');
    }
}
