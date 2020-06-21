<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProficiencyOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proficiency_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proficiency_id', false, true);
            $table->integer('race_id', false, true)->nullable();
            $table->integer('class_id', false, true)->nullable();


            $table->foreign('proficiency_id')->references('id')->on('proficiencies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('proficiency_options');
    }
}
