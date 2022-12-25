<template>
    <div class="tools" v-if="ui.hasTools.value">
        <div class="uk-accordion-title"><h2>Tools</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match" uk-grid>
                <div v-for="tool in ui.known.value">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                    </div>
                </div>
                <div v-for="(tool, index) in state.selection">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ tool.name }} ({{ tool.origin }})</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="state.remove(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="class-based"
                 v-if="Object.keys(info.availableClasses.value).length > 0 && ui.classToolCount.value > 0">
                <template v-for="(charClass, classIndex) in info.form.classes">
                    <div class="class"
                         v-if="info.availableClasses.value.hasOwnProperty(charClass.class_id)
                         && info.availableClasses.value[charClass.class_id]?.tool_choices > (state.classSelected?.[charClass.class_id] || 0)">
                        <h4>{{ info.availableClasses.value[charClass.class_id].name }}</h4>
                        <div class="uk-margin">
                            <label :for="`tools_${classIndex}`">
                                Choose {{
                                    info.availableClasses.value[charClass.class_id]?.tool_choices -
                                    state.classSelected?.[charClass.class_id] || 0
                                }}
                                tool proficiencies
                            </label>
                            <select :name="`tools_${classIndex}`" :id="`tools_${classIndex}`" class="uk-select"
                                    :class="{'uk-form-danger': ui.errors?.hasOwnProperty('proficiencies.tools')}"
                                    @input="state.addToClass(info.availableClasses.value[charClass.class_id], $event.target.value); $event.target.value = ''">
                                <option :value="null">- Make a choice -</option>
                                <option v-for="tool in ui.classTools.value[charClass.class_id].optional"
                                        :value="tool.id"
                                        :disabled="ui.classTools.value[charClass.class_id].known.includes(tool.id)">
                                    {{ tool.name }}
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
import { useProficiencyTypeMetrics, useSelectionState } from '../../composables/selection-states'
import { useSelectionUpdates } from '../../composables/selection-updates'

export default {
    name: 'tool-selection',
    props: ['errors', 'form', 'input'],
    setup(props, ctx) {
        onMounted(() => state.init())
        const { state, computed, isAlreadySelected } = useSelectionState(props, 'Tools')
        const { hasProficiencyType: hasTools, classProficiencyCount: classToolCount } = useProficiencyTypeMetrics(
            computed.raceProficiencies,
            computed.classProficiencies,
            computed.backgroundProficiencies
        )
        useSelectionUpdates(props, ctx, state)

        return {
            info: {
                availableClasses: computed.classes,
                form: props.form
            },
            state,
            ui: {
                backgroundTools: computed.backgroundProficiencies,
                classToolCount,
                classTools: computed.classProficiencies,
                errors: props.errors,
                hasTools,
                isAlreadySelected,
                known: computed.known,
                raceTools: computed.raceProficiencies
            }
        }
    }
}
</script>