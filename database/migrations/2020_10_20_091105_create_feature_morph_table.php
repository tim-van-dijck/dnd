<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureMorphTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_morph', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->unsignedInteger('entity_id');
            $table->unsignedInteger('feature_id');
            $table->tinyInteger('level');
            $table->tinyInteger('choose')->default(0);
            $table->timestamps();

            $table->index('entity_id');
            $table->foreign('feature_id')->references('id')->on('features')
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
        Schema::dropIfExists('feature_morph');
    }
}
