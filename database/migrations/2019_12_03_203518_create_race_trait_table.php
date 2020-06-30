<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceTraitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('race_trait', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('race_id', false, true);
            $table->integer('trait_id', false, true);

            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('trait_id')->references('id')->on('traits')
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
        Schema::dropIfExists('race_trait');
    }
}
