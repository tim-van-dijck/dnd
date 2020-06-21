<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeaponWeaponPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weapon_weapon_property', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weapon_id', false, true);
            $table->integer('weapon_property_id', false, true);

            $table->foreign('weapon_id')->references('id')->on('weapons')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('weapon_property_id')->references('id')->on('weapon_properties')
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
        Schema::dropIfExists('weapon_weapon_property');
    }
}
