<template>
    <aside id="left-col" class="uk-light uk-visible@m">
        <div class="left-logo uk-flex uk-flex-middle">
            DUNGEONS & DIARIES
        </div>

        <div class="left-nav-wrap" v-if="user && user.hasOwnProperty('permissions')">
            <ul class="uk-nav uk-nav-default" data-uk-nav>
                <li class="uk-nav-header">CAMPAIGN</li>
                <li v-if="$store.getters.can('view', 'character')">
                    <router-link :to="{name: 'player-characters'}">
                        <i class="fas fa-users fa-fw"></i> Characters
                    </router-link>
                </li>
                <li v-if="$store.getters.can('view', 'location')">
                    <router-link :to="{name: 'locations'}">
                        <i class="fas fa-map-marked-alt fa-fw"></i> Locations
                    </router-link>
                </li>
                <li v-if="$store.getters.can('view', 'quest')">
                    <router-link :to="{name: 'quests'}">
                        <i class="fas fa-exclamation fa-fw"></i> Quests
                    </router-link>
                </li>
                <li v-if="$store.getters.can('view', 'journal')">
                    <router-link :to="{name: 'journal'}">
                        <i class="fas fa-book-open fa-fw"></i> Journal
                    </router-link>
                </li>
                <li v-if="$store.getters.can('view', 'note')">
                    <router-link :to="{name: 'notes'}">
                        <i class="fas fa-file-alt fa-fw"></i> Notes
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name: 'inventories'}">
                        <i class="fas fa-shopping-bag fa-fw"></i> Inventory
                    </router-link>
                </li>
                <li class="uk-nav-header">PLATFORM</li>
                <li v-if="$store.getters.can('view', 'user')">
                    <router-link :to="{name: 'users'}">
                        <i class="fas fa-users-cog fa-fw"></i> Users
                    </router-link>
                </li>
                <li v-if="$store.getters.can('view', 'role')">
                    <router-link :to="{name: 'roles'}">
                        <i class="fas fa-user-lock fa-fw"></i> Campaign roles
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="bar-bottom">
            <ul class="uk-subnav uk-flex uk-flex-center uk-child-width-1-5" data-uk-grid>
                <li><router-link :to="{name: 'dashboard'}" title="Home"><i class="fas fa-home fa-fw"></i></router-link></li>
                <li><a href="/profile" title="Settings"><i class="fas fa-sliders-h fa-fw"></i></a></li>
                <li><spellbook-button icon name="spellbook-modal"></spellbook-button></li>
                <li>
                    <a href="/logout" title="Sign out" @click.prevent="$store.dispatch('logout')">
                        <i class="fas fa-sign-out-alt fa-fw"></i>
                    </a>
                </li>
            </ul>
        </div>
        <spellbook-modal />
    </aside>
</template>

<script>
    import {mapState} from 'vuex';
    import SpellbookModal from "../spells/partials/spellbook-modal";
    import SpellbookButton from "../spells/partials/spellbook-button";

    export default {
        name: "Navigation",
        components: {SpellbookButton, SpellbookModal},
        methods: {
            logout() {
                axios.post('/logout')
                    .then(() => {
                        document.location.href = '/';
                    });
            }
        },
        computed: {
            ...mapState(['user'])
        }
    }
</script>