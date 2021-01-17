<template>
    <div id="ability-tab">
        <div class="uk-child-width-1-6@m uk-child-width-1-1@s uk-grid-small uk-grid-match" uk-grid>
            <div v-for="ability in totalAbilities">
                <div class="uk-card uk-card-body uk-card-secondary">
                    <h3 class="uk-text-center uk-margin-remove uk-text-bold">{{ ability.name }}</h3>
                    <h3 class="uk-text-center uk-margin-top uk-margin-bottom"
                        :class="{'uk-text-danger': errors.hasOwnProperty(`ability_scores.${ability.name}`)}">
                        {{ ability.bonus >= 0 ? '+' : '-' }} {{ Math.abs(ability.bonus) }}
                    </h3>
                    <p class="uk-text-center uk-margin-remove"
                       :class="{'uk-text-danger': errors.hasOwnProperty(`ability_scores.${ability.name}`)}">
                        {{ ability.total }}
                        <span v-if="ability.total > ability.score">
                            ({{ ability.score }} + {{ bonuses[ability.name] }})
                        </span>
                    </p>
                    <p class="uk-text-center">
                        <input class="uk-input uk-width uk-text-center"
                               :class="{'uk-form-danger': errors.hasOwnProperty(`ability_scores.${ability.name}`)}"
                               type="number"
                               min="3"
                               :max="20 - bonuses[ability.name]" v-model="abilities[ability.name]">
                    </p>
                </div>
            </div>
        </div>
        <div class="race-selection">
            <div class="uk-margin">
                <div class="uk-label uk-label-primary selection-label" v-for="(choice, index) in choices.race" @click="choices.race.splice(index, 1)">
                    <span class="select-label-content">{{ choice.ability }} +{{ choice.score }} (Race)</span>
                    <span class="uk-padding-left uk-text-bold">&times;</span>
                </div>
                <div class="uk-label uk-label-primary selection-label" v-for="(choice, index) in choices.subrace" @click="choices.subrace.splice(index, 1)">
                    <span class="select-label-content">{{ choice.ability }} +{{ choice.score }} (Subrace)</span>
                    <span class="uk-text-bold">&times;</span>
                </div>
            </div>
            <div v-if="race && choices.race.length < race.optional_ability_bonuses" class="uk-margin">
                <h4>Race</h4>
                <label for="race-ability">
                    Choose {{ race.optional_ability_bonuses - choices.race.length }} ability bonuses
                </label>
                <select id="race-ability"
                        name="race-ability"
                        class="uk-select"
                        @input="addAbilityBonus($event.target.value); $event.target.value = '';">
                    <option :value="null">- Make a choice -</option>
                    <option v-for="ability in race.abilities.filter(item => item.optional)" :value="`${ability.ability}_${ability.bonus}`">
                        {{ ability.ability }} +{{ ability.bonus }}
                    </option>
                </select>
            </div>
            <div v-if="subrace && choices.subrace.length < subrace.optional_ability_bonuses" class="uk-margin">
                <h4>Subrace</h4>
                <label for="subrace-ability">
                    Choose {{ subrace.optional_ability_bonuses - choices.subrace.length }} ability bonuses
                </label>
                <select id="subrace-ability"
                        name="subrace-ability"
                        class="uk-select"
                        @input="addAbilityBonus($event.target.value); $event.target.value = '';">
                    <option :value="null">- Make a choice -</option>
                    <option v-for="ability in subrace.abilities.filter(item => item.optional)" :value="`${ability.ability}_${ability.bonus}`">
                        {{ ability.ability }} +{{ ability.bonus }}
                    </option>
                </select>
            </div>
        </div>

        <p class="uk-margin">
            <router-link class="uk-button uk-button-danger" :to="{name: 'player-characters'}">
                Cancel
            </router-link>
            <button class="uk-button uk-button-primary uk-align-right" @click.prevent="$emit('next')">Next <i class="fas fa-chevron-right"></i></button>
        </p>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "pc-form-abilities-tab",
        props: ['info', 'characterClasses', 'value'],
        data() {
            return {
                abilities: {
                    STR: 3,
                    DEX: 3,
                    CON: 3,
                    INT: 3,
                    WIS: 3,
                    CHA: 3
                },
                choices: {
                    race: [],
                    subrace: []
                }
            }
        },
        created() {
            if (this.value) {
                this.abilities = {
                    STR: (this.value.STR || 3) - (this.bonuses.STR || 0),
                    DEX: (this.value.DEX || 3) - (this.bonuses.DEX || 0),
                    CON: (this.value.CON || 3) - (this.bonuses.CON || 0),
                    INT: (this.value.INT || 3) - (this.bonuses.INT || 0),
                    WIS: (this.value.WIS || 3) - (this.bonuses.WIS || 0),
                    CHA: (this.value.CHA || 3) - (this.bonuses.CHA || 0)
                }
            }
        },
        methods: {
            addAbilityBonus(value) {
                let ability = value.split('_')[0];
                this.choices.race.push({ability,  score: value.split('_')[1]});
            }
        },
        computed: {
            ...mapState('Characters', {'availableClasses': 'classes', 'races': 'races', 'errors': 'errors'}),
            totalAbilities() {
                let abilities = [];
                for (let ability of ['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA']) {
                    let total = parseInt(this.abilities[ability]) + parseInt(this.bonuses[ability]);
                    abilities.push({
                        score: this.abilities[ability],
                        total,
                        name: ability,
                        bonus: Math.floor((total -10) / 2)
                    });
                }
                return abilities;
            },
            bonuses() {
                let bonuses = {
                    STR: 0,
                    DEX: 0,
                    CON: 0,
                    INT: 0,
                    WIS: 0,
                    CHA: 0
                };
                if (this.race) {
                    for (let ability of this.race.abilities) {
                        if (ability.optional) {
                            let chosen = this.choices.race.find(item => item.ability === ability.ability);
                            if (chosen) {
                                bonuses[ability.ability] += parseInt(chosen.score);
                            }
                        } else {
                            bonuses[ability.ability] += ability.bonus;
                        }
                    }
                    if (this.subrace) {
                        for (let ability of this.subrace.abilities) {
                            if (ability.optional) {
                                let chosen = this.choices.subrace.find(item => item.ability === ability.ability);
                                if (chosen) {
                                    bonuses[ability.ability] += parseInt(chosen.score);
                                }
                            } else {
                                bonuses[ability.ability] += ability.bonus;
                            }
                        }
                    }
                }
                return bonuses
            },
            race() {
                return this.races[this.info.race_id] || null;
            },
            raceOptions() {
                let options = [];
                if (this.race) {
                    let chosen = this.choices.race.map(item => item.ability)
                        .concat(this.choices.subrace.map(item => item.ability));
                    options = this.race.abilities.filter(item => !chosen.includes(item.ability));
                }
                return options;
            },
            subraceOptions() {
                let options = [];
                if (this.subrace) {
                    let chosen = this.choices.race.map(item => item.ability)
                        .concat(this.choices.subrace.map(item => item.ability));
                    options = this.subrace.abilities.filter(item => !chosen.includes(item.ability));
                }
                return options;
            },
            subrace() {
                if (this.race) {
                    return this.race.subraces.find(item => item.id === this.info.subrace_id) || null;
                }
                return null;
            }
        },
        watch: {
            totalAbilities() {
                let scores = {};
                for (let ability of this.totalAbilities) {
                    scores[ability.name] = ability.total;
                }
                this.$emit('input', scores);
            }
        }
    }
</script>