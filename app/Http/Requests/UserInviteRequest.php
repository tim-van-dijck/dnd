<?php

namespace App\Http\Requests;

use App\Models\Campaign\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserInviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = Auth::user();
        return Auth::user()->active && AuthService::userHasCampaignPermission($user, null, 'user', 'create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $campaignId = Session::get('campaign_id');
        /** @var Collection $roles */
        $roles = Role::where('campaign_id', $campaignId)->with('users')->get('id');
        $roleIds = $roles->pluck('id')->toArray();
        $emails = $roles->flatMap(fn (Role $role) => $role->users->map(fn (User $user) => $user->email));

        return [
            'email' => ['required', 'email', 'max:191', Rule::notIn($emails)],
            'role' => ['required', 'integer', Rule::in($roleIds)]
        ];
    }

    public function messages()
    {
        return [
            'email.not_in' => 'A user with this email address is already part of or has already been invited to this campaign.'
        ];
    }
}
