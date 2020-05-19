<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    /**
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(RoleRepository $roleRepository, Request $request)
    {
        $page = $request->query('page', []);
        $roles = $roleRepository->get(Session::get('campaign_id'), $page['number'] ?? 1, $page['size'] ?? 10);
        return RoleResource::collection($roles);
    }

    /**
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RoleRepository $roleRepository, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'permissions' => 'required|array|min:1',
            'permissions.*.id' => 'required|integer|exists:permissions,id',
            'permissions.*.view' => 'required|bool',
            'permissions.*.create' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean'
        ]);

        $roleRepository->store(Session::get('campaign_id'), $request->input());
    }

    /**
     * @param RoleRepository $roleRepository
     * @param int $roleId
     * @return Role
     */
    public function show(RoleRepository $roleRepository, int $roleId): RoleResource
    {
        return new RoleResource($roleRepository->find(Session::get('campaign_id'), $roleId));
    }

    /**
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @param int $roleId
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(RoleRepository $roleRepository, Request $request, int $roleId)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'permissions' => 'required|array|min:1',
            'permissions.*.id' => 'required|integer|exists:permissions,id',
            'permissions.*.view' => 'required|bool',
            'permissions.*.create' => 'required|boolean',
            'permissions.*.edit' => 'required|boolean',
            'permissions.*.delete' => 'required|boolean'
        ]);

        $roleRepository->update(Session::get('campaign_id'), $roleId, $request->input());
    }

    /**
     * @param RoleRepository $roleRepository
     * @param int $roleId
     * @throws \Exception
     */
    public function destroy(RoleRepository $roleRepository, int $roleId)
    {
        $roleRepository->destroy(Session::get('campaign_id'), $roleId);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        return response()->json(Permission::get());
    }
}