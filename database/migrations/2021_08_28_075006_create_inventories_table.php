<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id');
            $table->foreignId('character_id')->nullable();
            $table->integer('platinum')->default(0);
            $table->integer('gold')->default(0);
            $table->integer('silver')->default(0);
            $table->integer('electrum')->default(0);
            $table->integer('copper')->default(0);
            $table->timestamps();

            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('character_id')
                ->references('id')
                ->on('characters')
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
        Schema::dropIfExists('inventories');
    }
}
