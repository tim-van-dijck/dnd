<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Campaign\Permission;
use App\Models\Campaign\Role;
use App\Models\Campaign\UserPermission;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @return AnonymousResourceCollection
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
     * @throws ValidationException
     */
    public function store(RoleRepository $roleRepository, Request $request)
    {
        $this->authorize('create', Role::class);
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
     * @param Role $role
     * @return RoleResource
     * @throws AuthorizationException
     */
    public function show(Role $role): RoleResource
    {
        $this->authorize('view', $role);
        if (Session::get('campaign_id') != $role->campaign_id) {
            throw new ModelNotFoundException();
        }
        $role->load('permissions');
        return new RoleResource($role);
    }

    /**
     * @param RoleRepository $roleRepository
     * @param Request $request
     * @param Role $role
     * @throws ValidationException
     */
    public function update(RoleRepository $roleRepository, Request $request, Role $role)
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
        $roleRepository->update(Session::get('campaign_id'), $role, $request->input());
    }

    /**
     * @param RoleRepository $roleRepository
     * @param Role $role
     * @throws Exception
     */
    public function destroy(RoleRepository $roleRepository, Role $role)
    {
        $roleRepository->destroy(Session::get('campaign_id'), $role);
    }


    /**
     * @return JsonResponse
     */
    public function permissions()
    {
        return response()->json(Permission::get());
    }

    /**
     * @param string $entity
     * @param int $entityId
     * @return JsonResponse
     */
    public function customEntityPermissions(string $entity, int $entityId)
    {
        $permissions = UserPermission::where([
            'campaign_id' => Session::get('campaign_id'),
            'entity' => $entity,
            'entity_id' => $entityId
        ])->get()
            ->keyBy('user_id')
            ->map(fn ($permission) => [
                'view' => $permission->view,
                'create' => $permission->create,
                'edit' => $permission->edit,
                'delete' => $permission->delete
            ]);
        return response()->json($permissions);
    }
}