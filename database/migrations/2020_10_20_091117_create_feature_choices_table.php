<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureChoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_choices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('feature_id');
            $table->unsignedInteger('choice_id');
            $table->string('entity');
            $table->unsignedInteger('entity_id');
            $table->timestamps();

            $table->index('entity_id');
            $table->foreign('feature_id')->references('id')->on('features')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('choice_id')->references('id')->on('features')
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
        Schema::dropIfExists('feature_choices');
    }
}
