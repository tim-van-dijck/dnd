<template>
    <aside id="left-col" class="uk-light uk-visible@m">
        <div class="left-logo uk-flex uk-flex-middle">
            DUNGEONS & DIARIES
        </div>

        <div class="left-nav-wrap" v-if="user && user.hasOwnProperty('permissions')">
            <ul class="uk-nav uk-nav-default" data-uk-nav>
                <li class="uk-nav-header">PLATFORM</li>
                <li>
                    <router-link :to="{name: 'campaigns'}">
                        <i class="fas fa-feather-alt fa-fw"></i> Campaigns
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'users'}">
                        <i class="fas fa-users-cog fa-fw"></i> Users
                    </router-link>
                </li>
                <li class="uk-nav-header">CONTENT</li>
                <li>
                    <router-link :to="{name: 'races'}">
                        <i class="fas fa-users fa-fw"></i> Races
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'classes'}">
                        <i class="fas fa-hat-wizard fa-fw"></i> Classes
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'spells'}">
                        <i class="fas fa-hand-sparkles fa-fw"></i> Spells
                    </router-link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script>

import { useMainStore } from '@admin/stores/auth'

export default {
    name: 'Navigation',
    setup() {
        const store = useMainStore()
        return {
            user: store.user
        }
    }
}
</script>