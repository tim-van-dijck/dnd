<template>
    <aside id="left-col" class="uk-light uk-visible@m">
        <div class="left-logo uk-flex uk-flex-middle">
            DUNGEONS & DIARIES
        </div>

        <div class="left-nav-wrap" v-if="user && user.hasOwnProperty('permissions')">
            <ul class="uk-nav uk-nav-default" data-uk-nav>
                <template v-for="section in state.navigation.value">
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
                <li>
                    <router-link :to="{name: 'dashboard'}" title="Home"><i class="fas fa-home fa-fw"></i></router-link>
                </li>
                <li><a href="/profile" title="Settings"><i class="fas fa-sliders-h fa-fw"></i></a></li>
                <li>
                    <spellbook-button icon name="spellbook-modal"></spellbook-button>
                </li>
                <li>
                    <a href="/logout" title="Sign out" @click.prevent="state.logout()">
                        <i class="fas fa-sign-out-alt fa-fw"></i>
                    </a>
                </li>
            </ul>
        </div>
        <spellbook-modal/>
    </aside>
</template>

<script>
import { useMainStore } from '@campaign/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import SpellbookButton from '../../spells/spellbook-button'
import SpellbookModal from '../../spells/spellbook-modal'
import { useNavigation } from './navigation.state'

export default {
    name: 'Navigation',
    setup() {
        const store = useMainStore()
        const { user } = storeToRefs(store)
        const state = useNavigation(store)

        return {
            state,
            user
        }
    },
    components: { SpellbookButton, SpellbookModal }
}
</script>