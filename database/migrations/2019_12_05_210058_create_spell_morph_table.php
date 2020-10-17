<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpellMorphTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spell_morph', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->unsignedInteger('spell_id');
            $table->boolean('optional')->default(false);
            $table->tinyInteger('required_level')->default(1);

            $table->index('entity_id');
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
        Schema::dropIfExists('spell_morph');
    }
}
