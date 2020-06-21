<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id', false, true);
            $table->bigInteger('location_id', false, true)->nullable();
            $table->string('name');
            $table->string('type');
            $table->string('map');
            $table->text('description');
            $table->boolean('private')->default(false);
            $table->timestamps();

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
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
        Schema::dropIfExists('locations');
    }
}
