<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionalFeatsToRacesTable extends Migration
{
    public function up()
    {
        Schema::table('races', function (Blueprint $table) {
            $table->integer('optional_feats')->default(0)->after('optional_traits');
        });
    }

    public function down()
    {
        Schema::table('races', function (Blueprint $table) {
            $table->dropColumn('optional_feats');
        });
    }
}
