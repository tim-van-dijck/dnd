<template>
    <div id="spell-tab">
        <spellbook-button icon button name="spellbook-modal">Spellbook</spellbook-button>
        <div uk-grid>
            <div class="uk-width-2-3@s spells-known"
                 v-if="state.selection.cantrips.length > 0 || state.selection.spells.length > 0">
                <h2>Spells known</h2>
                <template v-for="(level, index) in ui.spellsKnown.value">
                    <div v-if="level.spells.length > 0 || (index === 0 && info.raceCantrip.value)"
                         class="spell-level">
                        <h3>{{ level.title }}</h3>
                        <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
                            <div v-for="spell in level.spells">
                                <div class="uk-card uk-card-body uk-card-primary">
                                    <div class="uk-card-title">{{ spell.name }}</div>
                                    <button
                                        v-if="state.selection[spell.level > 0 ? 'spells' : 'cantrips'].find(item => spell.id == item.id)"
                                        class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                        @click.prevent="state.remove(spell.level > 0 ? 'spells' : 'cantrips', spell.origin_type, spell.origin_id, spell.id)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <p>({{ spell.origin_name }})</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="uk-width-1-3@s" v-if="ui.classSpells">
                <h2>Spell selection</h2>
                <template v-for="(spells, level) in ui.classSpells">
                    <div
                        v-if="Object.keys(spells?.items || {}).length > 0 || (level === 0 && info.raceCantrips.value.length > 0)">
                        <h3>{{ spells.title }}</h3>
                        <div
                            :class="{'uk-form-horizontal': info.chosenClasses.value.length > 1 || info.raceCantrips.value.length > 0}">
                            <div class="uk-margin" v-if="level === 0 && info.raceCantrips.length > 0">
                                <label for="subrace_cantrip" class="uk-form-label">{{ info.subrace.name }}</label>
                                <div class="uk-form-controls">
                                    <select id="subrace_cantrip" class="uk-select" :disabled="info.raceCantrip != null"
                                            @input="state.selectRaceCantrip($event.target.value); $event.target.value = ''">
                                        <option value="">- Make a choice -</option>
                                        <option v-for="spell in info.raceCantrips" :value="spell.id">
                                            {{ spell.name }} ({{ spell.school }})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <template v-for="chosenClass in info.chosenClasses.value">
                                <div class="uk-margin" v-if="ui.classSpells.value[level].items[chosenClass.id]">
                                    <label v-if="info.chosenClasses.length > 1 || info.raceCantrips.length > 0"
                                           :for="`spells_${level}_${chosenClass.id}`" class="uk-form-label">
                                        {{ chosenClass.name }}
                                    </label>
                                    <div class="uk-form-controls">
                                        <select :id="`spells_${level}_${chosenClass.id}`" class="uk-select"
                                                :disabled="chosenClass.currentLevel[(level > 0) ? 'spells_known' : 'cantrips_known'] <= state.selection[level > 0 ? 'spells' : 'cantrips'].length"
                                                @input="state.selectSpell(level, chosenClass, $event.target.value); $event.target.value = ''">
                                            <option value="">
                                                - Make a choice ({{
                                                    `${chosenClass.currentLevel[(
                                                        level > 0
                                                    ) ? 'spells_known' : 'cantrips_known'] - state.selection[level > 0
                                                        ? 'spells'
                                                        : 'cantrips'].length} remaining`
                                                }}) -
                                            </option>
                                            <option v-for="spell in ui.classSpells[level].items[chosenClass.id]"
                                                    :value="spell.id">
                                                {{ spell.name }} ({{ spell.school }})
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">Cancel</router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="ui.next">Save</button>
        </p>
    </div>
</template>

<script>
import SpellbookButton from '@campaign/components/spells/spellbook-button'
import { computed, onMounted } from 'vue'
import { useRelated } from '../../PlayerCharacterForm.state'
import { usePlayerCharacterSpellsComputed, usePlayerCharacterSpellState } from './player-character-form-spell-tab.state'
import { useFormatteValues } from './player-character-form-spell-tab.ui'

export default {
    name: 'player-character-form-spell-tab',
    components: { SpellbookButton },
    props: ['errors', 'input', 'classes'],
    emits: ['update'],
    setup(props, ctx) {
        const { background, classes, race, subrace } = useRelated(props.input)
        const { raceCantrips } = usePlayerCharacterSpellsComputed(props.input, race, subrace)

        const test = computed(() => {
            return props.classes.map((c) => c.class_id).filter(Boolean).join(', ')
        })

        const chosenClasses = computed(() => {
            return props.input.classes.map((cl) => {
                if (classes.value.hasOwnProperty(cl.class_id)) {
                    const chosenClass = { ...classes.value[cl.class_id] }
                    if (cl.subclass_id) {
                        chosenClass.subclass = {
                            ...chosenClass.subclasses?.find(item => item.id === cl.subclass_id) || {}
                        }
                    }

                    return chosenClass
                }
                return null
            })
        })

        const { state, raceCantrip } = usePlayerCharacterSpellState(background, classes, race, subrace, raceCantrips)


        const ui = useFormatteValues(ctx, state.selection, chosenClasses)
        onMounted(() => state.init())

        return {
            info: {
                chosenClasses,
                raceCantrip,
                raceCantrips,
                classes,
                test
            },
            state,
            ui
        }
    }
}
</script>