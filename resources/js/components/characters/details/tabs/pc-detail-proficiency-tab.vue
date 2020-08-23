<template>
    <div id="proficiency-tab">
        <div class="languages uk-margin">
            <h2>Languages</h2>
            <div v-if="languages" class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-grid-small uk-grid-match" uk-grid>
                <div v-for="language in characterLanguages">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ language.name }}</div>
                        <p><em>{{ language.script ? language.script : 'No' }} script</em></p>
                    </div>
                </div>
            </div>
            <p v-else class="uk-text-center"><i class="fas fa-sync fa-spin fa-2x"></i></p>
        </div>
        <div class="skills uk-margin">
            <h2>Skills</h2>
            <div class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match" uk-grid>
                <div v-for="skill in proficiencies.skills">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ skill.name }}</div>
                        <p><em>({{ skill.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tools uk-margin">
            <h2>Tools</h2>
            <div v-if="proficiencies.tools && proficiencies.tools.length > 0"
                 class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match" uk-grid>
                <div v-for="tool in proficiencies.tools">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }}</div>
                        <p><em>({{ tool.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
            <div v-else class="uk-alert-primary" uk-alert>
                This character doesn't have Tool proficiencies
            </div>
        </div>
        <div class="instruments uk-margin">
            <h2>Instruments</h2>
            <div v-if="proficiencies.instruments && proficiencies.instruments.length > 0"
                 class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match" uk-grid>
                <div v-for="instrument in proficiencies.instruments">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }}</div>
                        <p><em>({{ instrument.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
            <div v-else class="uk-alert-primary" uk-alert>
                This character doesn't have Instrument proficiencies
            </div>
        </div>
        <div class="weapons-armor uk-margin">
            <h2>Weapons & armor</h2>
            <div v-if="(proficiencies.armor && proficiencies.armor.length > 0) || (proficiencies.weapons && proficiencies.weapons.length > 0)"
                 class="uk-child-width-1-2@s uk-child-width-1-3@l uk-child-width-1-4@xl uk-grid-small uk-grid-match" uk-grid>
                <div v-for="armor in proficiencies.armor">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ armor.name }}</div>
                        <p><em>({{ armor.origin }} proficiency)</em></p>
                    </div>
                </div>
                <div v-for="weapon in proficiencies.weapons">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ weapon.name }}</div>
                        <p><em>({{ weapon.origin }} proficiency)</em></p>
                    </div>
                </div>
            </div>
            <div v-else class="uk-alert-primary" uk-alert>
                This character doesn't have Instrument proficiencies
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "pc-detail-proficiency-tab",
        props: ['character', 'proficiencies'],
        created() {
            this.$store.dispatch('loadLanguages');
        },
        computed: {
            ...mapState(['languages']),
            characterLanguages() {
                return this.languages.filter((language) => this.proficiencies.languages.includes(language.id));
            },
            skills() {
                return this.proficiencies.skills.map((skill) => {

                });
            }
        }
    }
</script>

<style scoped>

</style>