<template>
    <div id="info-tab" uk-grid>
        <div class="uk-width-1-1@s uk-width-1-2@l uk-width-1-3@xl">
            <h2>Info</h2>
            <div class="uk-flex uk-flex-between">
                <b>Name</b>
                <span>{{ character.info.name }} <span v-if="character.info.dead" title="This character is dead">(<i
                    class="fas fa-skull"></i>)</span></span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Race</b>
                <span>{{
                        `${character.race.name} ${character.race.subrace
                            ? '(' + character.race.subrace.name + ')'
                            : ''}`
                    }}</span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Alignment</b>
                <span>{{ alignments[character.info.alignment] }}</span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Age</b>
                <span>{{ character.info.age }}</span>
            </div>
            <router-link v-if="character.info.inventory_id"
                         tag="button"
                         class="uk-button uk-margin uk-width"
                         :to="{name: 'inventory', params: {id: character.info.inventory_id}}">
                <i class="fas fa-shopping-bag"></i> To inventory
            </router-link>
            <div class="uk-width uk-margin">
                <h2>Race Traits</h2>
                <div class="uk-alert-primary" uk-alert>These aren't available yet.</div>
            </div>
        </div>
        <div class="uk-width-1-1@s uk-width-1-2@l uk-width-2-3@xl">
            <h2>Ability Scores</h2>
            <div class="uk-child-width-1-6@m uk-child-width-1-3 uk-grid-small uk-grid-match" uk-grid>
                <div v-for="(ability, name) in abilityScores">
                    <h3 class="uk-text-center uk-margin-remove uk-text-bold">{{ name }}</h3>
                    <h3 class="uk-text-center uk-margin-top uk-margin-bottom">
                        {{ ability.bonus }}
                    </h3>
                    <p class="uk-text-center uk-margin-remove">
                        {{ ability.score }}
                    </p>
                </div>
            </div>
            <h2>Bio</h2>
            <div v-if="character.info.bio && character.info.bio.length > 0"
                 class="uk-card uk-card-body uk-card-secondary"
                 v-html="character.info.bio"></div>
            <div v-else class="uk-alert-primary" uk-alert>This character has no bio</div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'

export default {
    name: 'pc-detail-character-tab',
    props: ['character'],
    setup(props) {
        const abilities = ['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA']
        const alignments = {
            'LG': 'Lawful Good',
            'NG': 'Neutral Good',
            'CG': 'Chaotic Good',
            'LN': 'Lawful Neutral',
            'TN': 'True Neutral',
            'CN': 'Chaotic Neutral',
            'LE': 'Lawful Evil',
            'NE': 'Neutral Evil',
            'CE': 'Chaotic Evil'
        }

        const abilityScores = computed(() => {
            const scores = {}
            for (const ability of abilities) {
                const bonus = Math.floor((
                    props.character.ability_scores[ability] - 10
                ) / 2)
                scores[ability] = {
                    score: props.character.ability_scores[ability],
                    bonus: `${bonus >= 0 ? '+' : '-'} ${Math.abs(bonus)}`
                }
            }
            return scores
        })

        return { abilities, abilityScores, alignments, character: props.character }
    }
}
</script>