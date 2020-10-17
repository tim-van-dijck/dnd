<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubracesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subraces', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('race_id');
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('optional_ability_bonuses');
            $table->tinyInteger('optional_languages');
            $table->tinyInteger('optional_proficiencies');
            $table->tinyInteger('optional_traits');

            $table->foreign('race_id')
                ->references('id')
                ->on('races')
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
        Schema::dropIfExists('subraces');
    }
}
