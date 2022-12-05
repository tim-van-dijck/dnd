<template>
    <div class="skills">
        <div class="uk-accordion-title"><h2>Skills</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-margin-bottom">
                <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                    <div v-for="skill in ui.known.value">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                        </div>
                    </div>
                    <div v-for="(skill, index) in (state.selection || [])">
                        <div class="uk-card uk-card-body uk-card-primary">
                            <div class="uk-card-title">{{ skill.name }} ({{ skill.origin }})</div>
                            <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                    @click.prevent="state.removeSkill(index)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="class-based" v-if="Object.keys(info.availableClasses).length > 0">
                <div class="class" v-for="(charClass, classIndex) in info.classes"
                     v-if="charClass?.class_id && info.availableClasses[charClass?.class_id]?.skill_choices > (state.classSelected[charClass?.class_id] || 0)">
                    <h4>Class: {{ info.availableClasses[charClass.class_id].name }}</h4>
                    <div class="uk-margin">
                        <label :for="`skills_${classIndex}`"
                               :class="{'uk-text-danger': ui.errors.hasOwnProperty('proficiencies.skills')}">
                            Choose {{
                                info.availableClasses[charClass.class_id].skill_choices - (
                                    state.classSelected[charClass.class_id] || 0
                                )
                            }} skill proficiencies
                        </label>
                        <select :name="`skills_${classIndex}`" :id="`skills_${classIndex}`" class="uk-select"
                                :class="{'uk-form-danger': ui.errors.hasOwnProperty('proficiencies.skills')}"
                                @input="state.addSkillToClass(info.availableClasses[charClass.class_id], $event.target.value); $event.target.value = ''">
                            <option :value="null">- Make a choice -</option>
                            <option v-for="skill in ui.classSkills[charClass.class_id].optional" :value="skill.id"
                                    :disabled="ui.classSkills[charClass.class_id].known.includes(skill.id)">
                                {{ skill.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, watch } from 'vue'
import { useSkillSelectionState } from './skill-selection.state'

export default {
    name: 'skill-selection',
    props: ['background', 'classes', 'race', 'subrace', 'value'],
    setup(props, ctx) {
        onMounted(() => state.init())
        const { classes, backgroundSkills, classSkills, known, raceSkills, state } = useSkillSelectionState(props)

        watch(() => props.classes, () => {
            const selection = []
            for (const charClass of props.classes) {
                const skills = state.selection
                    .filter((item) => item.origin_type == 'class' && item.origin_id == charClass.class_id)
                selection.concat(skills)
                state.setSelection(selection)
            }
        })
        watch(() => state.selection, () => ctx.emit('input', state.selection))

        return {
            info: {
                availableClasses: classes,
                classes: props.classes,
                errors: props.errors
            },
            state,
            ui: {
                backgroundSkills,
                classSkills,
                known,
                raceSkills
            }
        }
    }
}
</script>