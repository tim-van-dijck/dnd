<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->admin && Auth::user()->active;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:191', Rule::unique('users', 'name')->ignore($this->route('user'))]
        ];
    }
}
