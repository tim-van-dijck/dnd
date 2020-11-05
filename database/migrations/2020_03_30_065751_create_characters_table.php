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
            $table->unsignedInteger('race_id');
            $table->unsignedInteger('subrace_id')->nullable();
            $table->unsignedInteger('background_id')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->string('age');
            $table->string('alignment', 2);
            $table->boolean('dead')->default(false);
            $table->boolean('private')->default(false);
            $table->text('bio')->nullable();
            $table->json('ability_scores');
            $table->text('trait');
            $table->text('ideal');
            $table->text('bond');
            $table->text('flaw');
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('race_id')->references('id')->on('races')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subrace_id')->references('id')->on('subraces')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('background_id')->references('id')->on('backgrounds')
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
