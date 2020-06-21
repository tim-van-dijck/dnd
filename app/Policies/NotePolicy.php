<?php

namespace App\Policies;

use App\Models\Campaign\Note;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'note', 'view');
    }

    public function view(User $user, Note $note)
    {
        return AuthService::userHasCampaignPermission($user, $note, 'note', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'note', 'create');
    }

    public function update(User $user, Note $note)
    {
        return AuthService::userHasCampaignPermission($user, $note, 'note', 'edit');
    }

    public function destroy(User $user, Note $note)
    {
        return AuthService::userHasCampaignPermission($user, $note, 'note', 'update');
    }
}
