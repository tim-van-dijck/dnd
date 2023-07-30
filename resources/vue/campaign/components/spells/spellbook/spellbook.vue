<template>
    <div id="spellbook">
        <div class="filters">
            Level:
            <select class="uk-select uk-width-auto" v-model="state.filters.level">
                <option :value="0">Cantrips</option>
                <option v-for="level in 9" :value="level">{{ level }}</option>
            </select>
            <div class="uk-button-group">
                <button
                    :class="{'uk-button-primary': state.filters.ritual, 'uk-button-danger': state.filters.ritual === false}"
                    class="uk-button"
                    @click.prevent="state.toggleFilter('ritual')">
                    Ritual
                </button>
                <button
                    :class="{'uk-button-primary': state.filters.concentration, 'uk-button-danger': state.filters.concentration === false}"
                    class="uk-button"
                    @click.prevent="state.toggleFilter('concentration')">
                    Concentration
                </button>
            </div>
            Search:
            <input type="text" name="query" class="uk-input uk-width-auto" v-model="state.filters.query">
            <a v-if="state.filters.query.length > 0" class="uk-link-text" href="#"
               @click.prevent="state.filters.query = ''">
                <i class="fas fa-times"></i>
            </a>
        </div>
        <div uk-grid>
            <div id="spell-list" class="uk-width-1-4">
                <h3>Spells</h3>
                <ul class="uk-nav uk-nav-default" uk-overflow-auto>
                    <li :class="{'uk-active': state.spell && item.id === state.spell.id}"
                        v-for="item in relevantSpells">
                        <a href="#" @click.prevent="state.setSpell(item)">
                            {{ item.name }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="uk-width-3-4" v-if="state.spell">
                <h3>
                    {{ state.spell.name }}
                    <span v-if="state.spell.level > 0" class="uk-text-small">(level {{
                            state.spell.level
                        }} {{ state.spell.school }} spell)</span>
                    <span v-else class="uk-text-small">({{ state.spell.school }} cantrip)</span>
                </h3>
                <ul class="uk-list">
                    <li><b>Casting Time:</b> {{ state.spell.casting_time }}</li>
                    <li><b>Range:</b> {{ state.spell.range }}</li>
                    <li><b>Components:</b> {{ state.spell.components }}</li>
                    <li><b>Duration:</b> {{ state.spell.duration }}</li>
                    <li>
                        <b>Concentration:</b>
                        <i :class="`fas fa-${state.spell.concentration ? 'check' : 'times'} uk-text-${state.spell.concentration ? 'success' : 'danger'}`"></i>
                    </li>
                    <li>
                        <b>Ritual:</b>
                        <i :class="`fas fa-${state.spell.ritual ? 'check' : 'times'} uk-text-${state.spell.ritual ? 'success' : 'danger'}`"></i>
                    </li>
                    <li><b>Description:</b></li>
                </ul>
                <div uk-overflow-auto v-html="state.spell.description"></div>
                <template v-if="state.spell.higher_levels">
                    <p><b>At higher levels:</b></p>
                    <div v-html="state.spell.higher_levels"></div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { useSpellStore } from '@campaign/stores/spells'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted, watch } from 'vue'
import { useSpellBookRelevantSpells, useSpellBookState } from './spellbook.state'

export default {
    name: 'spellbook',
    setup() {
        const store = useSpellStore()
        const { spells } = storeToRefs(store)
        const state = useSpellBookState()
        const relevantSpells = useSpellBookRelevantSpells(spells, state.filters)

        onMounted(() => store.load())

        watch(state.filters, () => {
            if (!relevantSpells.value.includes(state.spell)) {
                state.setSpell(null)
            }
        })

        return { state, relevantSpells }
    }
}
</script>