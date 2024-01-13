<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserInviteRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->admin && Auth::user()->active;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')],
            'admin' => ['boolean']
        ];
    }
}
