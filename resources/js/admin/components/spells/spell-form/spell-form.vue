<template>
    <div>
        <h1>{{ ui.title }}</h1>
        <div class="uk-section uk-section-default">
            <div class="uk-container padded">
                <form v-if="state.spell" class="uk-form-stacked" uk-grid>
                    <div class="uk-width-1-2">
                        <div class="uk-margin">
                            <label for="name" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('name')}">Name*</label>
                            <input id="name" title="name" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('name')}"
                                   v-model="state.spell.name">
                        </div>
                        <div class="uk-margin">
                            <label for="level" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('level')}">Level*</label>
                            <select id="level" name="level" type="text" class="uk-input"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('level')}"
                                    v-model="state.spell.level">
                                <option value="">- Choose a level -</option>
                                <option v-for="level in 9" :value="level">{{
                                        level === 0 ? 'Cantrip' : `Level ${level}`
                                    }}
                                </option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="school" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('school')}">School*</label>
                            <select id="school" name="school" type="text" class="uk-input"
                                    :class="{'uk-form-danger': state.errors.hasOwnProperty('school')}"
                                    v-model="state.spell.school">
                                <option value="">- Choose a school -</option>
                                <option v-for="school in state.schools" :value="school">{{ school }}</option>
                            </select>
                        </div>
                        <div class="uk-margin">
                            <label for="range" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('range')}">Range*</label>
                            <input id="range" name="range" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('range')}"
                                   v-model="state.spell.range">
                        </div>
                        <div class="uk-margin">
                            <label for="duration" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('duration')}">Duration*</label>
                            <input id="duration" name="duration" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('duration')}"
                                   v-model="state.spell.duration">
                        </div>
                        <div class="uk-margin">
                            <label for="casting_time" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('casting_time')}">Casting
                                time*</label>
                            <input id="casting_time" name="casting_time" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('casting_time')}"
                                   v-model="state.spell.casting_time">
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('components')}">
                                Components*
                            </label>
                            <div class="uk-form-controls">
                                <label for="component_v">
                                    <input id="component_v" class="uk-checkbox" type="checkbox" value="V"
                                           v-model="state.spell.components"> Verbal
                                </label><br>
                                <label for="component_s">
                                    <input id="component_s" class="uk-checkbox" type="checkbox" value="S"
                                           v-model="state.spell.components"> Somatic
                                </label><br>
                                <label for="component_m">
                                    <input id="component_m" class="uk-checkbox" type="checkbox" value="M"
                                           v-model="state.spell.components"> Material
                                </label>
                            </div>
                        </div>
                        <div v-if="state.spell.components.includes('M')" class="uk-margin">
                            <label for="materials" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('materials')}">Materials</label>
                            <input id="materials" name="materials" type="text" class="uk-input"
                                   :class="{'uk-form-danger': state.errors.hasOwnProperty('materials')}"
                                   v-model="state.spell.materials">
                        </div>
                        <hr>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('ritual')}">
                                <input id="ritual" class="uk-checkbox" type="checkbox" v-model="state.spell.ritual">
                                Ritual
                            </label>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('concentration')}">
                                <input id="concentration" class="uk-checkbox" type="checkbox"
                                       v-model="state.spell.concentration"> Concentration
                            </label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <label for="description" class="uk-form-label"
                               :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">Description</label>
                        <html-editor id="description" name="description" v-model="state.spell.description"
                                     height="600"></html-editor>
                        <div class="uk-margin">
                            <label for="higher_levels" class="uk-form-label"
                                   :class="{'uk-text-danger': state.errors.hasOwnProperty('description')}">
                                At higher levels
                            </label>
                            <textarea id="higher_levels" class="uk-width" name="higher_levels"
                                      v-model="state.spell.higher_levels"></textarea>
                        </div>
                    </div>
                    <p class="uk-margin">
                        <button class="uk-button uk-button-primary" @click.prevent="state.save">Save</button>
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
import { useStore } from "vuex";
import HtmlEditor from "../../../../components/partial/html-editor";
import { onMounted } from "vue";
import { state } from "./spell-form.state";

export default {
    name: "spell-form",
    props: ['id'],
    components: { HtmlEditor },
    setup(props) {
        const store = useStore()
        onMounted(() => {
            if (props.id) {
                store.dispatch('Spells/find', props.id)
                    .then((spell) => {
                        spell.components = spell.components.split(',');
                        state.spell = spell
                    });
            }
        })

        return {
            state,
            ui: {
                title: () => props.id ? `Edit ${state.spell ? state.spell.name : 'spell'}` : 'Add spell'
            }
        }
    }
}
</script>