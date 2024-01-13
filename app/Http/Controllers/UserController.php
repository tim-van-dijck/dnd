<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrentUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile()
    {
        return view('profile.form', ['user' => Auth::user()]);
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(Auth::user()->id)
            ],
            'old_password' => [
                'required_with:password',
                'nullable',
                'string',
                'min:16',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]/',
                function ($attribute, $value, $fail) {
                    if (bcrypt($value) != Auth::user()->getAuthPassword()) {
                        $fail('Invalid password');
                    }
                }
            ],
            'password' => ['nullable', 'string', 'min:16', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]/']
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return redirect()->route('profile.index');
    }

    public function me(): CurrentUserResource
    {
        return new CurrentUserResource(Auth::user());
    }
}