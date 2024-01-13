<?php

namespace App\Http\Controllers;

use App\Models\InviteCode;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function invitation(InviteCode $token)
    {
        return view('auth.register', ['token' => $token]);
    }

    public function registerInvitation(UserRepository $userRepository, Request $request, InviteCode $token)
    {
        $regex = AuthService::PASSWORD_REGEX;
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:16', 'confirmed', "regex:$regex"],
        ]);

        $userRepository->register($token, $request->input());
        return redirect('/');
    }
}
