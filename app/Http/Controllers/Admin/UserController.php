<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Campaign\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(UserRepository $userRepository, Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page');
        $filters = $request->query('filters', []);
        $users = $userRepository->get($filters, $page['number'] ?? 1, $page['size'] ?? 10);
        return UserResource::collection($users);
    }

    public function show(User $user): JsonResponse
    {
        return new JsonResponse($user);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function invite(UserRepository $userRepository, Request $request)
    {
        $campaignId = Session::get('campaign_id');
        $roles = implode(',', Role::where('campaign_id', $campaignId)->get('id')->pluck('id')->toArray());
        $this->validate($request, [
            'email' => 'required|email|max:191',
            'role' => "required|integer|in:$roles"
        ]);

        $userRepository->invite($campaignId, $request->input('email'), $request->input('role'));
    }

    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @param User $user
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserRepository $userRepository, Request $request, User $user)
    {
        $this->authorize('update', $user);
        $campaignId = Session::get('campaign_id');
        $roles = implode(',', Role::where('campaign_id', $campaignId)->get('id')->pluck('id')->toArray());
        $this->validate($request, [
            'role' => "required|integer|in:$roles"
        ]);

        $userRepository->updateRole($campaignId, $user, $request->input('role'));
    }

    public function destroy(User $user)
    {
        $user->roles()->sync([]);
        $user->permissions()->delete();
        $user->delete();
    }
}