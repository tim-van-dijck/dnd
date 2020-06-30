<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Campaign\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * @param UserRepository $userRepository
     * @param Request $request
     * @return mixed
     */
    public function index(UserRepository $userRepository, Request $request)
    {
        $page = $request->query('page');
        $users = $userRepository->get(Session::get('campaign_id'), $page['number'] ?? 1, $page['size'] ?? 10);
        return UserResource::collection($users);
    }

    /**
     * @param UserRepository $userRepository
     * @param Request $request
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

    public function me()
    {
        $permissions = AuthService::campaignPermissions(Session::get('campaign_id'));
        return response()->json([
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'permissions' => $permissions
        ]);
    }
}