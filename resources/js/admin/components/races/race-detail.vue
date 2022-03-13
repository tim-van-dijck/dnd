<template>
    <div v-if="race">
        <h1>
            <router-link class="uk-link-text" :to="{name: 'races'}">
                <i class="fas fa-chevron-left"></i>
            </router-link>
            {{ race.name }}
        </h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <div v-if="race" uk-grid>
                    <div class="uk-width-1-1@s uk-width-1-2@l uk-width-2-3@xl">
                        <h2>Description</h2>
                        <div v-html="race.description"></div>
                        <template v-if="race.subraces.length > 0">
                            <h2>Subraces</h2>
                            <div class="uk-child-width" uk-grid>
                                <div v-for="subrace in race.subraces">
                                    <div class="uk-card uk-card-body uk-card-secondary">
                                        <div class="uk-card-title">{{ subrace.name }}</div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="uk-width-1-1@s uk-width-1-2@l uk-width-1-3@xl">
                        <h2>Stats</h2>
                        <div class="uk-width uk-flex uk-flex-between">
                            <b>Size</b>
                            <span>{{ race.size }}</span>
                        </div>
                        <div class="uk-width uk-flex uk-flex-between">
                            <b>Speed</b>
                            <span>{{ race.speed }}ft.</span>
                        </div>
                        <div class="uk-width uk-flex uk-flex-between">
                            <b>Ability Bonuses</b>
                            <div>
                                <ul class="uk-list">
                                    <li v-for="ability in race.ability_bonuses.filter((item) => !item.optional)">
                                        {{ ability.ability }} +{{ ability.bonus }}
                                    </li>
                                    <li v-if="race.optional_ability_bonuses > 0">{{ race.optional_ability_bonuses }} optional bonuses</li>
                                </ul>
                            </div>
                        </div>
                        <div v-if="race.proficiencies.length > 0" class="uk-width uk-flex uk-flex-between">
                            <b>Proficiencies</b>
                            <div>
                                <ul class="uk-list">
                                    <li v-for="proficiency in race.proficiencies">{{ proficiency.name }}<template v-if="proficiency.optional"> (optional)</template></li>
                                </ul>
                            </div>
                        </div>
                        <div class="uk-width uk-flex uk-flex-between">
                            <b>Languages</b>
                            <div>
                                <ul class="uk-list">
                                    <li v-for="language in race.languages.filter(item => !item.optional)">{{ language.name }}</li>
                                    <li v-if="race.optional_languages > 0">{{ race.optional_languages }} optional languages</li>
                                </ul>
                            </div>
                        </div>
                        <div v-if="race.optional_feats > 0" class="uk-width uk-flex uk-flex-between">
                            <b>Feat choices</b>
                            <span>{{ race.optional_feats }}</span>
                        </div>
                        <div v-if="race.traits.length > 0" class="uk-margin uk-width">
                            <h2>Race Traits</h2>
                            <div class="uk-child-width" uk-grid>
                                <div v-for="trait in race.traits">
                                    <div class="uk-card uk-card-body uk-card-secondary">
                                        <div class="uk-card-title">{{ trait.name }}</div>
                                        <div v-html="trait.description"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "race-detail",
    props: ['id'],
    data() {
        return {
            race: null
        }
    },
    created() {
        this.$store.dispatch('Races/find', this.id)
            .then((race) => {
                this.race = race;
            })
    }
}
</script>

<style scoped>

</style>