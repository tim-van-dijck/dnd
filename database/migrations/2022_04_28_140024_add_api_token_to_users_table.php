<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 80)
                ->after('password')
                ->unique()
                ->nullable()
                ->default(null);
        });

        $users = User::whereNull('api_token')->get();
        foreach ($users as $user) {
            $user->api_token = Str::random(80);
            $user->save();
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
};
