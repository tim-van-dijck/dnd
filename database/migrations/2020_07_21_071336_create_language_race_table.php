<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageRaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_morph', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->unsignedSmallInteger('language_id');
            $table->boolean('optional')->default(false);
            $table->timestamps();

            $table->index('entity_id');
            $table->foreign('language_id')->references('id')->on('languages')
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
        Schema::dropIfExists('language_morph');
    }
}
