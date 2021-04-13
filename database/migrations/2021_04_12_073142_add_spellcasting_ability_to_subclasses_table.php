<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpellcastingAbilityToSubclassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subclasses', function (Blueprint $table) {
            $table->string('spellcasting_ability', 3)->nullable()->after('spellcaster');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subclasses', function (Blueprint $table) {
            $table->dropColumn('spellcasting_ability');
        });
    }
}
