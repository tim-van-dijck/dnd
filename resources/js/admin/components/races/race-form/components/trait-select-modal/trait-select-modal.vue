<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #trait-select-modal">
            Select race trait
        </button>
        <div id="trait-select-modal" uk-modal @beforehide="state.reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a race trait</h2>
                <div class="uk-margin">
                    <select name="trait" id="trait" class="uk-select" v-model="state.trait.id">
                        <option :value="0">- Choose a trait -</option>
                        <option v-for="option in traitOptions" :value="option.id"
                                :disabled="selected.includes(option.id)">{{ option.name }}
                        </option>
                    </select>
                </div>
                <div v-if="selectedTrait" v-html="selectedTrait.description"></div>
                <hr>
                <div uk-accordion>
                    <div class="accordion">
                        <div class="uk-accordion-title">
                            <h2>or create a new one</h2>
                        </div>
                        <div class="uk-accordion-content">
                            <div class="uk-margin">
                                <label for="trait-name" class="uk-form-label">Name*</label>
                                <input id="trait-name" name="name" type="text" class="uk-input"
                                       v-model="state.trait.name">
                            </div>
                            <div class="uk-margin">
                                <label for="trait-description" class="uk-form-label">Description</label>
                                <html-editor id="trait-description" name="trait-description"
                                             v-model="state.trait.description"
                                             height="600"></html-editor>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="uk-margin">
                    <button class="uk-button uk-button-primary" type="button" @click.prevent="state.save">Select
                    </button>
                </div>
                <button class=" uk-modal-close-default uk-close-large" type="button" uk-close></button>
            </div>
        </div>
    </div>
</template>

<script>
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import { onMounted } from 'vue'
import HtmlEditor from '../../../../../../components/partial/html-editor'
import { useRaceStore } from '../../../../../stores/races'
import { useState } from './trait-select-modal.state'
import { ui } from './trait-select-modal.ui'

export default {
    name: 'trait-select-modal',
    components: { HtmlEditor },
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    setup(props, ctx) {
        const store = useRaceStore()
        const { traits } = storeToRefs(store)
        const { state, selectedTrait, traitOptions } = useState(traits, ctx, ui)

        onMounted(() => {
            store.loadTraits()
            ui.initEditorOnShow()
            ui.removeEditorOnHide()
        })

        return { state, selectedTrait, traitOptions }
    }
}
</script>