<?php

namespace App\Providers;

use App\Models\Campaign\Campaign;
use App\Models\Campaign\Location;
use App\Models\Campaign\Note;
use App\Models\Campaign\Quest;
use App\Models\Campaign\Role;
use App\Models\Character\Character;
use App\Policies\CampaignPolicy;
use App\Policies\CharacterPolicy;
use App\Policies\LocationPolicy;
use App\Policies\NotePolicy;
use App\Policies\QuestPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Campaign::class => CampaignPolicy::class,
         Character::class => CharacterPolicy::class,
         Location::class => LocationPolicy::class,
         Note::class => NotePolicy::class,
         Quest::class => QuestPolicy::class,
         Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
