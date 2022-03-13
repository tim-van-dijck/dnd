<template>
    <div>
        <h1>{{ title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="spell" class="uk-form-stacked" uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" title="name" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('name')}"
                                   v-model="spell.name">
                        </div>
                        <div class="uk-margin">
                            <label for="level" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('level')}">Level*</label>
                            <select id="level" name="level" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('level')}"
                                   v-model="spell.level">
                                <option value="">- Choose a level -</option>
                                <option v-for="level in 9" :value="level">{{ level === 0 ? 'Cantrip' : `Level ${level}` }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="school" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('school')}">School*</label>
                            <select id="school" name="school" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('school')}"
                                   v-model="spell.school">
                                <option value="">- Choose a school -</option>
                                <option v-for="school in schools" :value="school">{{ school }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="range" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('range')}">Range*</label>
                            <input id="range" name="range" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('range')}"
                                   v-model="spell.range">
                        </div>
                        <div class="uk-margin">
                            <label for="duration" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('duration')}">Duration*</label>
                            <input id="duration" name="duration" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('duration')}"
                                   v-model="spell.duration">
                        </div>
                        <div class="uk-margin">
                            <label for="casting_time" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('casting_time')}">Casting time*</label>
                            <input id="casting_time" name="casting_time" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('casting_time')}"
                                   v-model="spell.casting_time">
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('components')}">Components*</label>
                            <div class="uk-form-controls">
                                <label for="component_v">
                                    <input id="component_v" class="uk-checkbox" type="checkbox" value="V" v-model="spell.components"> Verbal
                                </label><br>
                                <label for="component_s">
                                    <input id="component_s" class="uk-checkbox" type="checkbox" value="S" v-model="spell.components"> Somatic
                                </label><br>
                                <label for="component_m">
                                    <input id="component_m" class="uk-checkbox" type="checkbox" value="M" v-model="spell.components"> Material
                                </label>
                            </div>
                        </div>
                        <div v-if="spell.components.includes('M')" class="uk-margin">
                            <label for="materials" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('materials')}">Materials</label>
                            <input id="materials" name="materials" type="text" class="uk-input" :class="{'uk-form-danger': errors.hasOwnProperty('materials')}"
                                   v-model="spell.materials">
                        </div>
                        <hr>
                        <div class="uk-margin">
                            <label class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('ritual')}">
                                <input id="ritual" class="uk-checkbox" type="checkbox" v-model="spell.ritual"> Ritual
                            </label>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('concentration')}">
                                <input id="concentration" class="uk-checkbox" type="checkbox" v-model="spell.concentration"> Concentration
                            </label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <label for="description" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">Description</label>
                        <html-editor id="description" name="description" v-model="spell.description" height="600"></html-editor>
                        <div class="uk-margin">
                            <label for="higher_levels" class="uk-form-label" :class="{'uk-text-danger': errors.hasOwnProperty('description')}">At higher levels</label>
                            <textarea id="higher_levels" class="uk-width" name="higher_levels" v-model="spell.higher_levels"></textarea>
                        </div>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="save">Save</button>
                        <router-link class="uk-button uk-button-danger" :to="{name: 'spells'}">
                            Cancel
                        </router-link>
                    </p>
                </form>
                <p v-else class="uk-text-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import HtmlEditor from "../../../components/partial/html-editor";

    export default {
        name: "spell-form",
        props: ['id'],
        components: {HtmlEditor},
        data() {
            return {
                spell: {
                    name: '',
                    range: '',
                    components: [],
                    materials: '',
                    ritual: false,
                    concentration: false,
                    duration: '',
                    casting_time: '',
                    level: '',
                    school: '',
                    description: '',
                    higher_levels: ''
                },
                schools: [
                    'Abjuration',
                    'Conjuration',
                    'Divination',
                    'Enchantment',
                    'Evocation',
                    'Illusion',
                    'Necromancy',
                    'Transmutation'
                ]
            }
        },
        created() {
            if (this.id) {
                this.$store.dispatch('Spells/find', this.id)
                    .then((spell) => {
                        spell.components = spell.components.split(',');
                        this.spell = spell;
                    });
            }
        },
        computed: {
            ...mapState('Spells', ['errors']),
            title() {
                if (this.id) {
                    return 'Edit ' + (this.spell ? this.spell.name : 'spell');
                } else {
                    return 'Add spell';
                }
            }
        },
        methods: {
            save() {
                let promise;
                if (this.id > 0) {
                    promise = this.$store.dispatch('Spells/update', {id: this.id, spell: this.spell});
                } else {
                    promise = this.$store.dispatch('Spells/store', {spell: this.spell})
                }

                promise
                    .then(() => {
                        this.$router.push({name: 'spells'});
                    })
                    .catch((error) => {
                        this.$store.commit('Spells/SET_ERRORS', error.response.data.errors);
                        this.$store.dispatch('Messages/error', error.response.data.message, {root: true});
                    });
            }
        }
    }
</script>