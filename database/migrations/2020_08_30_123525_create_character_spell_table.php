<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterSpellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_spell', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('character_id');
            $table->unsignedInteger('spell_id');
            $table->string('origin_type');
            $table->unsignedInteger('origin_id');
            $table->timestamps();

            $table->foreign('character_id')->references('id')->on('characters')
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
        Schema::dropIfExists('character_spell');
    }
}
