<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id', false, true);
            $table->bigInteger('player_id', false, true)->nullable();
            $table->integer('race_id', false, true);
            $table->string('name');
            $table->string('title');
            $table->string('type');
            $table->string('age');
            $table->boolean('dead')->default(false);
            $table->boolean('private')->default(false);
            $table->text('bio');
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('player_id')->references('id')->on('users')
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
        Schema::dropIfExists('characters');
    }
}
