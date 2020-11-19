<template>
    <div id="race-list">
        <button class="uk-button uk-button-secondary" @click.prevent="open">Race list</button>
        <div id="race-info-modal" uk-modal>
            <div class="uk-width-expand uk-modal-dialog uk-modal-body">
                <h2 v-if="activeRace" class="uk-modal-title">{{ activeRace.name }}</h2>
                <h2 v-else class="uk-modal-title">Races</h2>
                <div uk-grid>
                    <div class="uk-width-1-5">
                        <ul class="uk-nav uk-nav-default">
                            <li :class="{'uk-active': active.race && race.id === active.race}"
                                v-for="race in races">
                                <a href="#" @click.prevent="setActive(race)">
                                    {{ race.name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="uk-width-4-5" v-if="activeRace">
                        <div v-if="activeRace">
                            <ul uk-tab>
                                <li :class="{'uk-active': active.tab === 'description'}">
                                    <a href="#" @click.prevent="active.tab = 'description'">Description</a>
                                </li>
                                <li v-if="activeRace.traits.length > 0" :class="{'uk-active': active.tab === 'traits'}">
                                    <a href="#" @click.prevent="active.tab = 'traits'">Traits</a>
                                </li>
                                <li v-if="activeRace.subraces.length > 0" :class="{'uk-active': active.tab === 'subraces'}">
                                    <a href="#" @click.prevent="active.tab = 'subraces'">Subraces</a>
                                </li>
                            </ul>
                            <div v-show="active.tab === 'description'">
                                <ul class="uk-list">
                                    <li><b>Ability Scores:</b> {{ activeRace.ability_scores }}</li>
                                    <li v-if="activeRace.proficiencies.skills.length > 0"><b>Skills:</b> {{ activeRace.proficiencies.skills.join(', ') }}</li>
                                    <li v-if="activeRace.proficiencies.tools.length > 0"><b>Tools:</b> {{ activeRace.proficiencies.tools.join(', ') }}</li>
                                    <li v-if="activeRace.proficiencies.weapons.length > 0"><b>Weapons:</b> {{ activeRace.proficiencies.weapons.join(', ') }}</li>
                                    <li v-if="activeRace.proficiencies.armor.length > 0"><b>Armor:</b> {{ activeRace.proficiencies.armor.join(', ') }}</li>
                                    <li v-if="activeRace.optional_proficiencies > 0">
                                        <b>Choose {{ activeRace.optional_proficiencies }} of:</b> {{ activeRace.proficiencies.optional.join(', ') }}
                                    </li>
                                </ul>
                                <h3>Race description</h3>
                                <div  v-html="activeRace.description" uk-overflow-auto></div>
                            </div>
                            <div v-if="activeRace.traits.length > 0" v-show="active.tab === 'traits'" uk-grid>
                                <div class="uk-width-1-3">
                                    <ul class="uk-nav uk-nav-default">
                                        <li :class="{'uk-active': active.trait === trait.id}"
                                            v-for="trait in activeRace.traits">
                                            <a href="#" @click.prevent="active.trait = trait.id">
                                                {{ trait.name }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="uk-width-2-3 class-specs">
                                    <h4>{{ activeTrait.name }}</h4>
                                    <div v-html="activeTrait.description"></div>
                                </div>
                            </div>
                            <div v-show="active.tab === 'subraces'">
                                <ul uk-tab>
                                    <li :class="{'uk-active': active.subrace == subrace.id}" v-for="subrace in activeRace.subraces">
                                        <a href="#" @click.prevent="setActiveSubrace(subrace.id)">{{ subrace.name }}</a>
                                    </li>
                                </ul>
                                <div v-if="active.subrace == subrace.id" class="subrace-info" v-for="subrace in activeRace.subraces">
                                    <div class="class-description" v-html="subrace.description"></div>
                                    <ul class="uk-list">
                                        <li v-if="activeSubrace.ability_scores.length > 0"><b>Ability Scores:</b> {{ activeSubrace.ability_scores }}</li>
                                        <li v-if="activeSubrace.proficiencies.skills.length > 0"><b>Skills:</b> {{ activeSubrace.proficiencies.skills.join(', ') }}</li>
                                        <li v-if="activeSubrace.proficiencies.tools.length > 0"><b>Tools:</b> {{ activeSubrace.proficiencies.tools.join(', ') }}</li>
                                        <li v-if="activeSubrace.proficiencies.weapons.length > 0"><b>Weapons:</b> {{ activeSubrace.proficiencies.weapons.join(', ') }}</li>
                                        <li v-if="activeSubrace.proficiencies.armor.length > 0"><b>Armor:</b> {{ activeSubrace.proficiencies.armor.join(', ') }}</li>
                                        <li v-if="activeSubrace.optional_proficiencies > 0">
                                            <b>Choose {{ activeSubrace.optional_proficiencies }} of:</b> {{ activeSubrace.proficiencies.optional.join(', ') }}
                                        </li>
                                    </ul>
                                    <h3>Traits</h3>
                                    <div uk-grid>
                                        <div class="uk-width-1-3">
                                            <ul class="uk-nav uk-nav-default">
                                                <li :class="{'uk-active': active.subTrait === trait.id}"
                                                    v-for="trait in activeSubrace.traits">
                                                    <a href="#" @click.prevent="active.subTrait = trait.id">
                                                        {{ trait.name }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div v-if="activeSubraceTrait" class="uk-width-2-3">
                                            <h4>{{ activeSubraceTrait.name }}</h4>
                                            <div v-html="activeSubraceTrait.description"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="uk-text-center" v-else>
                            <i class="fas fa-2x fa-sync fa-spin"></i>
                        </p>
                    </div>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';;
    import UIKit from 'uikit';

    export default {
        name: "race-info-modal",
        data() {
            return {
                active: {
                    race: null,
                    subrace: null,
                    tab: 'description',
                    trait: null,
                    subTrait: null
                }
            }
        },
        methods: {
            open() {
                UIKit.modal(`#race-info-modal`).show();
            },
            setActive(race) {
                this.active.race = race.id;
                this.active.tab = 'description';
                if (race.traits.length > 0) {
                    this.active.trait = race.traits[0].id;
                }
                if (race.subraces.length > 0) {
                    let subrace = race.subraces[0];
                    this.active.subrace = subrace.id;
                    this.active.subTrait = subrace.traits[0].id;
                } else {
                    this.active.subrace = null;
                    this.active.subTrait = null;
                }
            },
            setActiveSubrace(subraceId) {
                let subrace = this.activeRace.subraces.find(item => item.id === subraceId);
                this.active.subrace = subraceId;
                this.active.subTrait = subrace.traits[0].id;
            }
        },
        computed: {
            ...mapState('Characters', ['races']),
            activeRace() {
                if (this.races && this.active.race && this.races[this.active.race]) {
                    let race = JSON.parse(JSON.stringify(this.races[this.active.race]));
                    race.ability_scores = race.abilities
                        .map((item) => {
                            return `${item.ability} +${item.bonus}`;
                        })
                        .join(', ');
                    race.proficiencies = {
                        armor: race.proficiencies.filter(item => item.type === 'Armor' && !item.optional).map(item => item.name),
                        skills: race.proficiencies.filter(item => item.type === 'Skills' && !item.optional).map(item => item.name),
                        tools: race.proficiencies.filter(item => item.type === 'Tools' && !item.optional).map(item => item.name),
                        weapons: race.proficiencies.filter(item => item.type === 'Weapons' && !item.optional).map(item => item.name),
                        optional: race.proficiencies.filter(item => item.optional).map(item => item.name)
                    }
                    return race;
                }
                return null;
            },
            activeSubrace() {
                if (this.activeRace && this.active.subrace) {
                    let subraceIndex = this.activeRace.subraces.findIndex(item => item.id === this.active.subrace);
                    if (subraceIndex != null) {
                        let subrace = JSON.parse(JSON.stringify(this.activeRace.subraces[subraceIndex]));
                        subrace.ability_scores = subrace.abilities
                            .map((item) => {
                                return `${item.ability} +${item.bonus}`;
                            })
                            .join(', ');
                        subrace.proficiencies = {
                            armor: subrace.proficiencies.filter(item => item.type === 'Armor' && !item.optional).map(item => item.name),
                            skills: subrace.proficiencies.filter(item => item.type === 'Skills' && !item.optional).map(item => item.name),
                            tools: subrace.proficiencies.filter(item => item.type === 'Tools' && !item.optional).map(item => item.name),
                            weapons: subrace.proficiencies.filter(item => item.type === 'Weapons' && !item.optional).map(item => item.name),
                            optional: subrace.proficiencies.filter(item => item.optional).map(item => item.name)
                        };
                        return subrace;
                    }
                }
                return null;
            },
            activeTrait() {
                if (this.activeRace && this.active.trait) {
                    let index = this.activeRace.traits.findIndex(item => item.id === this.active.trait);
                    if (index >= 0) {
                        return JSON.parse(JSON.stringify(this.activeRace.traits[index]));
                    }
                }
                return null;
            },
            activeSubraceTrait() {
                if (this.activeSubrace && this.active.subTrait) {
                    let index = this.activeSubrace.traits.findIndex(item => item.id === this.active.subTrait);
                    if (index >= 0) {
                        return JSON.parse(JSON.stringify(this.activeSubrace.traits[index]));
                    }
                }
                return null;
            }
        },
        watch: {
            races: {
                deep: true,
                handler(value) {
                    if (value) {
                        let firstRace = value[Object.keys(value)[0]];
                        this.setActive(firstRace);
                    }
                }
            }
        }
    }
</script>