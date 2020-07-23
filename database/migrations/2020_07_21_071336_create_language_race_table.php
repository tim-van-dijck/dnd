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
        Schema::create('language_race', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('race_id');
            $table->unsignedInteger('subrace_id')->nullable();
            $table->unsignedSmallInteger('language_id');
            $table->boolean('optional')->default(false);
            $table->timestamps();

            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('subrace_id')->references('id')->on('subraces')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('language_race');
    }
}
