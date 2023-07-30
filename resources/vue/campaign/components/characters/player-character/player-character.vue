<template>
    <div id="pc-detail" v-if="character">
        <h1>
            <router-link class="uk-link-text"
                         :to="{name: character.info.type == 'player' ? 'player-characters' : 'npcs'}">
                <i class="fas fa-chevron-left"></i>
            </router-link>
            {{ character.info.name }} <span v-if="character.info.private" title="This character is private">(<i
            class="fas fa-user-secret"></i>)</span>
        </h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <ul class="uk-tab">
                    <li :class="{'uk-active': ui.tab === 'character'}">
                        <a @click.prevent="ui.setTab('character')">Character</a>
                    </li>
                    <li :class="{'uk-active': ui.tab === 'class'}">
                        <a @click.prevent="ui.setTab('class')">Class</a>
                    </li>
                    <li :class="{'uk-active': ui.tab === 'proficiency'}">
                        <a @click.prevent="ui.setTab('proficiency')">Languages, Skills & Proficiencies</a>
                    </li>
                    <li :class="{'uk-active': ui.tab === 'ideal'}">
                        <a @click.prevent="ui.setTab('ideal')">Personality</a>
                    </li>
                    <li v-if="isCaster" :class="{'uk-active': ui.tab === 'spells'}">
                        <a @click.prevent="ui.setTab('spells')">Spells</a>
                    </li>
                </ul>
                <player-character-character-tab v-show="ui.tab === 'character'" :character="character"/>
                <player-character-class-tab v-show="ui.tab === 'class'" :classes="character.classes"/>
                <player-character-proficiency-tab v-show="ui.tab === 'proficiency'"
                                                  :proficiencies="character.proficiencies"/>
                <player-character-personality-tab v-show="ui.tab === 'ideal'" :personality="character.personality"/>
                <player-character-spells-tab v-show="ui.tab === 'spells'" :spells="character.spells"/>
            </div>
        </div>
    </div>
    <div v-else class="uk-section uk-section-default">
        <p class="uk-text-center">
            <i class="fas fa-sync fa-spin fa-2x"></i>
        </p>
    </div>
</template>

<script>
import { computed, onMounted, ref } from 'vue'
import { useCharacterStore } from '../../../stores/characters'
import PlayerCharacterCharacterTab from './components/player-character-character-tab'
import PlayerCharacterClassTab from './components/player-character-class-tab'
import PlayerCharacterPersonalityTab from './components/player-character-personality-tab'
import PlayerCharacterProficiencyTab from './components/player-character-proficiency-tab'
import PlayerCharacterSpellsTab from './components/player-character-spells-tab'
import { useCharacterDetailTabs } from './player-character.ui'

export default {
    name: 'player-character',
    components: {
        PlayerCharacterClassTab,
        PlayerCharacterSpellsTab,
        PlayerCharacterPersonalityTab,
        PlayerCharacterCharacterTab,
        PlayerCharacterProficiencyTab
    },
    props: ['id'],
    setup(props) {
        const store = useCharacterStore()
        const ui = useCharacterDetailTabs()
        const character = ref(null)

        onMounted(() => {
            store.loadClasses()
            store.find(props.id).then((result) => character.value = result)
        })

        const isCaster = computed(() => {
            return character.value.spells.cantrips.length > 0 || character.value.spells.spells.length > 0
        })

        return { character, ui, isCaster }
    }
}
</script>