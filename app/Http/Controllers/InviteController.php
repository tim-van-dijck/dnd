<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function invitation(string $token)
    {
        $user = User::where('invite_code', $token)->firstOrFail();
        return view('auth.register', ['token' => $token]);
    }

    public function registerInvitation(UserRepository $userRepository, Request $request, string $token)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'password' => ['required', 'string', 'min:16', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]/'],
        ]);

        $userRepository->register($token, $request->input());
        return redirect('/');
    }
}
