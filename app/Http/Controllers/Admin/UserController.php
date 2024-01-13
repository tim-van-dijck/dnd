<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserInviteRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    public function index(UserRepository $userRepository, Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page');
        $filters = $request->query('filters', []);
        $users = $userRepository->get($filters, $page['number'] ?? 1, $page['size'] ?? 10);
        return UserResource::collection($users);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function invite(UserRepository $userRepository, UserInviteRequest $request)
    {
        $this->authorize('create', User::class);
        $userRepository->inviteAdmin($request->input('email'), $request->input('admin', false));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserRepository $userRepository, UserRequest $request, User $user): UserResource
    {
        $this->authorize('update', $user);
        return new UserResource($userRepository->update($user, $request->input()));
    }

    public function resetPassword(Request $request): Response
    {
        $user = User::findOrFail($request->input('user_id'));
        $status = Password::sendResetLink($user->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return new Response('OK', 200);
        } else {
            return new Response('Something went wrong', 500);
        }
    }

    public function destroy(User $user)
    {
        $user->roles()->sync([]);
        $user->permissions()->delete();
        $user->delete();
    }
}