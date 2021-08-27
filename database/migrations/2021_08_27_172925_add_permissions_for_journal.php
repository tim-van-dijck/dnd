<?php

use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsForJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Permission $permission */
        $permission = Permission::query()->create(['name' => 'journal']);
        $roles = Role::where('system', 1)->get();
        /** @var Role $role */
        foreach ($roles as $role) {
            $role->permissions()->attach($permission->id, ['view' => 1, 'create' => 1, 'edit' => 1, 'delete' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
