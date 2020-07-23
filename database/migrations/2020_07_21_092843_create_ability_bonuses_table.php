<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilityBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ability_bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('race_id');
            $table->unsignedInteger('subrace_id')->nullable();
            $table->string('ability');
            $table->integer('bonus');
            $table->integer('optional')->default(false);

            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('subrace_id')->references('id')->on('subraces')
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
        Schema::dropIfExists('ability_bonuses');
    }
}
