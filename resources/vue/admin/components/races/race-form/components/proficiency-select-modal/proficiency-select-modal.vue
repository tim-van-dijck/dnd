<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #proficiency-select-modal">
            Select proficiency
        </button>
        <div id="proficiency-select-modal" uk-modal @beforehide="state.reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a proficiency</h2>
                <div class="uk-margin">
                    <select name="proficiency" id="proficiency" class="uk-select" v-model="state.proficiency.id">
                        <option :value="0">- Choose a proficiency -</option>
                        <optgroup v-for="(options, type) in proficiencyOptions" :label="type">
                            <option v-for="option in options" :value="option.id"
                                    :disabled="selected.includes(option.id)">{{ option.name }}
                            </option>
                        </optgroup>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="proficiency-optional">
                        <input id="proficiency-optional" class="uk-checkbox" type="checkbox"
                               v-model="state.proficiency.optional"> Optional
                    </label>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary" type="button" @click.prevent="state.save">
                        Select
                    </button>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import { useRaceStore } from '@admin/stores/races'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { computed, onMounted } from 'vue'
import { useState } from './proficiency-select-modal.state'

export default {
    name: 'proficiency-select-modal',
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    setup(props, ctx) {
        const store = useRaceStore()
        const { proficiencies } = storeToRefs(store)
        const ui = {
            close: () => UIKit.modal('#proficiency-select-modal').hide()
        }
        const state = useState(proficiencies, ctx, ui)
        onMounted(() => store.loadProficiencies())

        const proficiencyOptions = computed(() => {
            const proficiencyOptions = {}
            for (const proficiency of (
                proficiencies.value || []
            )) {
                if (!proficiencyOptions.hasOwnProperty(proficiency.type)) {
                    proficiencyOptions[proficiency.type] = []
                }
                proficiencyOptions[proficiency.type].push(proficiency)
            }
            return proficiencyOptions
        })

        return { state, proficiencyOptions }
    }
}
</script>