<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * @param int $campaignId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function get(int $campaignId, int $page, int $pageSize): LengthAwarePaginator
    {
        return User::join('role_user', 'role_user.user_id', '=', 'user.id')
            ->join('roles', function ($join) use ($campaignId) {
                $join->on('role.id', '=', 'role_user.role_id')
                    ->where('campaign_id', $campaignId);
            })
            ->paginate($pageSize, ['*'], 'page[number]', $page);
    }

    public function store(int $campaignId, string $email, int $roleId)
    {
        $codes = User::get(['invite_code'])->pluck('invite_code')->toArray();
        $user = new User();
        $user->name = '';
        $user->email = $email;
        $user->password = '';
        do {
            $user->invite_code = Str::random(16);
        } while (in_array($user->invite_code, $codes));
        $user->save();

        $user->grantRole($campaignId, $roleId);
    }

    public function find()
    {

    }

    public function update()
    {

    }
}