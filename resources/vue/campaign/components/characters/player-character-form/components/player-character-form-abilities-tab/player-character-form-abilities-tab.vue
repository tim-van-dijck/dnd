<template>
    <div id="ability-tab">
        <div class="uk-child-width-1-6@m uk-child-width-1-1@s uk-grid-small uk-grid-match" uk-grid>
            <div v-for="ability in ui.totalAbilities.value">
                <div class="uk-card uk-card-body uk-card-secondary">
                    <h3 class="uk-text-center uk-margin-remove uk-text-bold">{{ ability.name }}</h3>
                    <h3 class="uk-text-center uk-margin-top uk-margin-bottom"
                        :class="{'uk-text-danger': ui.errors.hasOwnProperty(`ability_scores.${ability.name}`)}">
                        {{ ability.bonus >= 0 ? '+' : '-' }} {{ Math.abs(ability.bonus) }}
                    </h3>
                    <p class="uk-text-center uk-margin-remove"
                       :class="{'uk-text-danger': ui.errors.hasOwnProperty(`ability_scores.${ability.name}`)}">
                        {{ ability.total }}
                        <span v-if="ability.total > ability.score">
                            ({{ ability.score }} + {{ ui.bonuses.value[ability.name] }})
                        </span>
                    </p>
                    <p class="uk-text-center">
                        <input class="uk-input uk-width uk-text-center"
                               :class="{'uk-form-danger': ui.errors.hasOwnProperty(`ability_scores.${ability.name}`)}"
                               type="number"
                               min="3"
                               :max="20 - ui.bonuses.value[ability.name]" v-model="state.input[ability.name]">
                    </p>
                </div>
            </div>
        </div>
        <div class="race-selection">
            <div class="uk-margin">
                <div class="uk-label uk-label-primary selection-label" v-for="(choice, index) in state.choices.race"
                     @click="state.choices.race.splice(index, 1)">
                    <span class="select-label-content">{{ choice.ability }} +{{ choice.score }} (Race)</span>
                    <span class="uk-padding-left uk-text-bold">&times;</span>
                </div>
                <div class="uk-label uk-label-primary selection-label" v-for="(choice, index) in state.choices.subrace"
                     @click="state.choices.subrace.splice(index, 1)">
                    <span class="select-label-content">{{ choice.ability }} +{{ choice.score }} (Subrace)</span>
                    <span class="uk-text-bold">&times;</span>
                </div>
            </div>
            <div v-if="state.choices.race.length < info.race.value?.optional_ability_bonuses" class="uk-margin">
                <h4>Race</h4>
                <label for="race-ability">
                    Choose {{ info.race.value.optional_ability_bonuses - state.choices.race.length }} ability bonuses
                </label>
                <select id="race-ability"
                        name="race-ability"
                        class="uk-select"
                        @input="state.addAbilityBonus($event.target.value); $event.target.value = ''">
                    <option value="">- Make a choice -</option>
                    <option v-for="ability in info.race.value.abilities.filter(item => item.optional)"
                            :value="`${ability.ability}_${ability.bonus}`">
                        {{ ability.ability }} +{{ ability.bonus }}
                    </option>
                </select>
            </div>
            <div v-if="state.choices.subrace.length < info.subrace.value?.optional_ability_bonuses"
                 class="uk-margin">
                <h4>Subrace</h4>
                <label for="subrace-ability">
                    Choose {{ info.subrace.value.optional_ability_bonuses - state.choices.subrace.length }} ability
                    bonuses
                </label>
                <select id="subrace-ability"
                        name="subrace-ability"
                        class="uk-select"
                        @input="state.addAbilityBonus($event.target.value); $event.target.value = ''">
                    <option value="">- Make a choice -</option>
                    <option v-for="ability in info.subrace.value.abilities.filter(item => item.optional)"
                            :value="`${ability.ability}_${ability.bonus}`">
                        {{ ability.ability }} +{{ ability.bonus }}
                    </option>
                </select>
            </div>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right"
                    @click.prevent="ui.next">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import { usePlayerCharacterAbilitiesState } from './player-character-form-abilities-tab.state'

export default {
    name: 'player-character-form-abilities-tab',
    props: ['errors', 'input'],
    setup(props, ctx) {
        const { state, computed } = usePlayerCharacterAbilitiesState(props, ctx)
        onMounted(() => state.init)

        return {
            info: {
                race: computed.race,
                subrace: computed.subrace
            },
            state,
            ui: {
                bonuses: computed.bonuses,
                errors: props.errors,
                next: () => ctx.emit('next'),
                totalAbilities: computed.totalAbilities
            }
        }
    }
}
</script>