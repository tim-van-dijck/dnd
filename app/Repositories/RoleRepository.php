<?php

namespace App\Repositories;

use App\Models\Campaign\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository
{
    /**
     * @param int $campaignId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, int $page, int $pageSize): LengthAwarePaginator
    {
        return Role::where('campaign_id', $campaignId)->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    /**
     * @param int $campaignId
     * @param array $data
     */
    public function store(int $campaignId, array $data)
    {
        $role = new Role();
        $role->campaign_id = $campaignId;
        $role->name = $data['name'];
        $role->save();
        $this->syncPermissions($data['permissions'], $role);
    }

    /**
     * @param int $campaignId
     * @param Role $role
     * @param array $data
     */
    public function update(int $campaignId, Role $role, array $data)
    {
        if ($campaignId != $role->campaign_id) {
            throw new ModelNotFoundException();
        }
        $role->name = $data['name'];
        $role->save();
        $this->syncPermissions($data['permissions'], $role);
    }

    /**
     * @param int $campaignId
     * @param Role $role
     * @throws \Exception
     */
    public function destroy(int $campaignId, Role $role)
    {
        if ($campaignId != $role->campaign_id) {
            throw new ModelNotFoundException();
        }
        $role->permissions()->sync([]);
        $role->delete();
    }

    /**
     * @param array $input
     * @param Role $role
     */
    public function syncPermissions(array $input, Role $role): void
    {
        $permissions = [];
        foreach ($input as $permission) {
            $permissions[$permission['id']] = [
                'view' => $permission['view'] ?? false,
                'create' => $permission['create'] ?? false,
                'edit' => $permission['edit'] ?? false,
                'delete' => $permission['delete'] ?? false
            ];
        }
        $role->permissions()->sync($permissions);
    }
}