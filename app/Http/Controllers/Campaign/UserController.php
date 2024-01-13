<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use App\Http\Resources\UserResource;
use App\Models\Campaign\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(UserRepository $userRepository, Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page');
        $users = $userRepository->getByCampaign(Session::get('campaign_id'), $page['number'] ?? 1, $page['size'] ?? 10);
        return UserResource::collection($users);
    }

    /**
     * @throws ValidationException
     */
    public function invite(UserRepository $userRepository, UserInviteRequest $request): void
    {
        $campaignId = Session::get('campaign_id');
        $userRepository->invite($campaignId, $request->input('email'), $request->input('role'));
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(UserRepository $userRepository, Request $request, User $user): void
    {
        $this->authorize('update', $user);
        $campaignId = Session::get('campaign_id');
        $roles = implode(',', Role::where('campaign_id', $campaignId)->get('id')->pluck('id')->toArray());
        $this->validate($request, [
            'role' => "required|integer|in:$roles"
        ]);

        $userRepository->updateRole($campaignId, $user, $request->input('role'));
    }

    public function destroy(User $user): void
    {
        $user->revokeRoles(Session::get('campaign_id'));
    }
}