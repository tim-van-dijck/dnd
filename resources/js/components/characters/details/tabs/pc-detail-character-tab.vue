<template>
    <div id="info-tab" uk-grid>
        <div class="uk-width-1-1@s uk-width-1-2@l uk-width-1-3@xl">
            <h2>Info</h2>
            <div class="uk-flex uk-flex-between">
                <b>Name</b>
                <span>{{ character.info.name }} <span v-if="character.info.dead" title="This character is dead">(<i class="fas fa-skull"></i>)</span></span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Race</b>
                <span>{{ `${character.race.name} ${character.race.subrace ? '(' + character.race.subrace.name + ')' : ''}` }}</span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Alignment</b>
                <span>{{ alignments[character.info.alignment] }}</span>
            </div>
            <div class="uk-flex uk-flex-between">
                <b>Age</b>
                <span>{{ character.info.age }}</span>
            </div>
            <div class="uk-width uk-margin">
                <h2>Classes</h2>
                <div class="uk-card uk-card-body uk-card-secondary" v-for="charClass in character.classes">
                    <div class="uk-card-title">
                        Level {{ charClass.level }}
                        {{ charClass.subclass ? charClass.subclass.name : '' }}
                        {{ charClass.name }}
                    </div>
                </div>
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
    export default {
        name: "pc-detail-character-tab",
        props: ['character'],
        data() {
            return {
                abilities: ['STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA'],
                alignments: {
                    'LG': 'Lawful Good',
                    'NG': 'Neutral Good',
                    'CG': 'Chaotic Good',
                    'LN': 'Lawful Neutral',
                    'TN': 'True Neutral',
                    'CN': 'Chaotic Neutral',
                    'LE': 'Lawful Evil',
                    'NE': 'Neutral Evil',
                    'CE': 'Chaotic Evil',
                }
            }
        },
        computed: {
            abilityScores() {
                let scores = {};
                for (let ability of this.abilities) {
                    let bonus = Math.floor((this.character.ability_scores[ability] - 10) / 2)
                    scores[ability] = {
                        score: this.character.ability_scores[ability],
                        bonus: `${bonus > 0 ? '+' : '-'} ${Math.abs(bonus)}`
                    };
                }
                return scores;
            }
        }
    }
</script>