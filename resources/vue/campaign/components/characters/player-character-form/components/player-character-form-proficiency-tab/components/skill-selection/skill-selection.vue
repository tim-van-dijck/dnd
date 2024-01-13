<template>
    <div class="skills">
        <div class="uk-accordion-title"><h2>Skills</h2></div>
        <div class="uk-accordion-content">
            <div v-if="ui.known.value.length > 0 || state.selection.length > 0" class="uk-margin-bottom">
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="skill in ui.known.value">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                        </div>
                    </div>
                    <div v-for="(skill, index) in state.selection">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="state.remove(index)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="Object.keys(info.availableClasses.value).length > 0">
                <template v-for="(charClass, classIndex) in info.form.classes">
                    <div class="class"
                         v-if="info.availableClasses.value.hasOwnProperty(charClass.class_id)
                         && info.availableClasses.value[charClass.class_id]?.skill_choices > (state.classSelected?.[charClass?.class_id] || 0)">
                        <h4>Class: {{ info.availableClasses.value[charClass.class_id].name }}</h4>
                        <div class="uk-margin">
                            <label :for="`skills_${classIndex}`"
                                   :class="{'uk-text-danger': ui.errors?.hasOwnProperty('proficiencies.skills')}">
                                Choose {{
                                    info.availableClasses.value[charClass.class_id]?.skill_choices - (
                                        state.classSelected?.[charClass.class_id] || 0
                                    )
                                }} skill proficiencies
                            </label>
                            <select :name="`skills_${classIndex}`" :id="`skills_${classIndex}`" class="uk-select"
                                    :class="{'uk-form-danger': ui.errors?.hasOwnProperty('proficiencies.skills')}"
                                    @input="state.addToClass(info.availableClasses.value[charClass.class_id], $event.target.value); $event.target.value = ''">
                                <option value="">- Make a choice -</option>
                                <option v-for="skill in ui.classSkills.value[charClass.class_id].optional"
                                        :value="skill.id"
                                        :disabled="ui.isAlreadySelected(charClass.class_id, skill.id)">
                                    {{ skill.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import { useSelectionState } from '../../composables/selection-states'
import { useSelectionUpdates } from '../../composables/selection-updates'

export default {
    name: 'skill-selection',
    props: ['errors', 'form', 'input'],
    setup(props, ctx) {
        onMounted(() => state.init())
        const { state, computed, isAlreadySelected } = useSelectionState(props, 'Skills')
        useSelectionUpdates(props, ctx, state)

        return {
            info: {
                availableClasses: computed.classes,
                form: props.form
            },
            state,
            ui: {
                backgroundSkills: computed.backgroundProficiencies,
                classSkills: computed.classProficiencies,
                errors: props.errors,
                isAlreadySelected,
                known: computed.known,
                raceSkills: computed.raceProficiencies
            }
        }
    }
}
</script>