<?php

namespace App\Repositories;

use App\Models\Campaign\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

        $permissions = [];
        foreach ($data['permissions'] as $permission) {
            $permissions[$permission['id']] = [
                'view' => $permission['view'] ?? false,
                'create' => $permission['create'] ?? false,
                'edit' => $permission['edit'] ?? false,
                'delete' => $permission['delete'] ?? false
            ];
        }
        $role->permissions()->sync($permissions);
    }

    /**
     * @param int $campaignId
     * @param int $roleId
     * @return Role
     */
    public function find(int $campaignId, int $roleId): Role
    {
        return Role::where([
            'campaign_id' => $campaignId,
            'id' => $roleId
        ])
            ->with('permissions')
            ->firstOrFail();
    }

    /**
     * @param int $campaignId
     * @param int $roleId
     * @param array $data
     */
    public function update(int $campaignId, int $roleId, array $data)
    {
        /** @var Role $role */
        $role = Role::where(['campaign_id' => $campaignId, 'id' => $roleId])->firstOrFail();
        $role->name = $data['name'];
        $role->save();

        $permissions = [];
        foreach ($data['permissions'] as $permission) {
            $permissions[$permission['id']] = [
                'view' => $permission['view'] ?? false,
                'create' => $permission['create'] ?? false,
                'edit' => $permission['edit'] ?? false,
                'delete' => $permission['delete'] ?? false
            ];
        }
        $role->permissions()->sync($permissions);
    }

    /**
     * @param int $campaignId
     * @param int $roleId
     * @throws \Exception
     */
    public function destroy(int $campaignId, int $roleId)
    {
        /** @var Role $role */
        $role = Role::where(['campaign_id' => $campaignId, 'id' => $roleId])->firstOrFail();
        $role->permissions()->sync([]);
        $role->delete();
    }
}