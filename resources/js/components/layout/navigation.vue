<template>
    <aside id="left-col" class="uk-light uk-visible@m">
        <div class="left-logo uk-flex uk-flex-middle">
            DUNGEONS & DIARIES
        </div>

        <div class="left-nav-wrap" v-if="user && user.hasOwnProperty('permissions')">
            <ul class="uk-nav uk-nav-default" data-uk-nav>
                <template v-for="section in navigation">
                    <li class="uk-nav-header">{{ section.title }}</li>
                    <li v-for="item in section.items" :class="{'uk-open': $route.name === item.to.name}">
                        <router-link :to="item.to">
                            <i :class="`fas fa-${item.icon} fa-fw`"></i> {{ item.title }}
                        </router-link>
                    </li>
                </template>
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
    import {mapGetters, mapState} from 'vuex';
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
            },
            ...mapGetters(['can'])
        },
        computed: {
            ...mapState(['user']),
            navigation() {
                const navigation =  [
                    {
                        title: "CAMPAIGN",
                        items: [
                            {
                                name: "character",
                                title: "Characters",
                                to: {name: "player-characters"},
                                icon: "users",
                            },
                            {
                                name: "location",
                                title: "Locations",
                                to: {name: "locations"},
                                icon: "map-marked-alt"
                            },
                            {
                                name: "quest",
                                title: "Quests",
                                to: {name: "quests"},
                                icon: "exclamation"
                            },
                            {
                                name: "journal",
                                title: "Journal",
                                to: {name: "journal"},
                                icon: "book-open",
                            },
                            {
                                name: "note",
                                title: "Notes",
                                to: {name: "notes"},
                                icon: "file-alt"
                            },
                            {
                                name: "inventory",
                                title: "Inventory",
                                to: {name: "inventories"},
                                icon: "shopping-bag",
                            }
                        ]
                    },
                    {
                        title: "PLATFORM",
                        items: [
                            {
                                name: "user",
                                title: "Users",
                                to: {name: "users"},
                                icon: "users-cog"
                            },
                            {
                                name: "role",
                                title: "Campaign Roles",
                                to: {name: "roles"},
                                icon: "user-lock"
                            }
                        ]
                    }
                ];

                return navigation.filter(section => {
                    section.items = section.items.filter((item) => this.can('view', item.name))
                    return section.items.length > 0
                })
            }
        }
    }
</script>