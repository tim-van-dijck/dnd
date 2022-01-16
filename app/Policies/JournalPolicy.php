<?php

namespace App\Policies;

use App\Models\Campaign\JournalEntry;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'journal', 'view');
    }

    public function view(User $user, JournalEntry $journalEntry)
    {
        return AuthService::userHasCampaignPermission($user, $journalEntry, 'journal', 'view');
    }

    public function create(User $user)
    {
        return AuthService::userHasCampaignPermission($user, null, 'note', 'create');
    }

    public function update(User $user, JournalEntry $journalEntry)
    {
        return AuthService::userHasCampaignPermission($user, $journalEntry, 'journal', 'edit');
    }

    public function destroy(User $user, JournalEntry $journalEntry)
    {
        return AuthService::userHasCampaignPermission($user, $journalEntry, 'journal', 'update');
    }
}
