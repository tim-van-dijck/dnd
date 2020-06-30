<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quest_objectives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quest_id', false, true);
            $table->string('name', 255);
            $table->boolean('optional')->default(false);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('quest_id')->references('id')->on('quests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quest_objectives');
    }
}
