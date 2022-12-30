<template>
    <div class="instruments" v-if="ui.hasInstruments.value">
        <div class="uk-accordion-title"><h2>Instruments</h2></div>
        <div class="uk-accordion-content">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-small uk-grid-match uk-margin-bottom" uk-grid>
                <div v-for="instrument in ui.known.value">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                    </div>
                </div>
                <div v-for="(instrument, index) in state.selection">
                    <div class="uk-card uk-card-body uk-card-primary">
                        <div class="uk-card-title">{{ instrument.name }} ({{ instrument.origin }})</div>
                        <button class="uk-text-danger uk-float-right uk-button uk-button-primary uk-button-round"
                                @click.prevent="state.remove(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="class-based"
                 v-if="Object.keys(info.availableClasses.value).length > 0 && ui.classInstrumentCount.value > 0">
                <template v-for="(charClass, classIndex) in info.form.classes">
                    <div class="class"
                         v-if="info.availableClasses.value.hasOwnProperty(charClass.class_id)
                         && info.availableClasses.value[charClass.class_id]?.instrument_choices > (state.classSelected[charClass.class_id] || 0)">
                        <h4>Class: {{ info.availableClasses.value[charClass.class_id].name }}</h4>
                        <div class="uk-margin">
                            <label :for="`instruments_${classIndex}`">
                                Choose {{
                                    info.availableClasses.value[charClass.class_id]?.instrument_choices -
                                    state.classSelected[charClass.class_id] || 0
                                }} instrument proficiencies
                            </label>
                            <select :name="`instruments_${classIndex}`" :id="`instruments_${classIndex}`"
                                    class="uk-select"
                                    @input="state.addToClass(info.availableClasses.value[charClass.class_id], $event.target.value); $event.target.value = ''">
                                <option value="">- Make a choice -</option>
                                <option v-for="instrument in ui.classInstruments.value[charClass.class_id].optional"
                                        :value="instrument.id"
                                        :disabled="ui.classInstruments.value[charClass.class_id].known.includes(instrument.id)">
                                    {{ instrument.name }}
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
    name: 'instrument-selection',
    props: ['errors', 'form', 'input'],
    setup(props, ctx) {
        onMounted(() => state.init())
        const { state, computed, isAlreadySelected } = useSelectionState(props, 'Instruments')
        const { hasProficiencyType, classProficiencyCount } = useProficiencyTypeMetrics(
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
                backgroundInstruments: computed.backgroundProficiencies,
                classInstrumentCount: classProficiencyCount,
                classInstruments: computed.classProficiencies,
                errors: props.errors,
                hasInstruments: hasProficiencyType,
                isAlreadySelected,
                known: computed.known,
                raceInstruments: computed.raceProficiencies
            }
        }
    },
    watch: {
        background: {
            deep: true,
            handler() {
                let selection = this.selection.filter((item) => item.origin_type !== 'background')
                this.$set(this, 'selection', selection)
            }
        },
        classes: {
            deep: true,
            handler() {
                let selection = []
                for (let charClass of this.classes) {
                    let instruments = this.selection.filter((item) => {
                        return item.origin_type === 'class' && item.origin_id === charClass.class_id
                    })
                    selection.concat(instruments)
                }
                selection.concat(this.selection.filter((item) => item.origin_type !== 'class'))
                this.$set(this, 'selection', selection)
            }
        }
    }
}
</script>