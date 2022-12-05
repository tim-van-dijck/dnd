<template>
    <div>
        <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #lang-select-modal">
            Select language
        </button>
        <div id="lang-select-modal" uk-modal @beforehide="state.reset()">
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title">Select a language proficiency</h2>
                <div class="uk-margin">
                    <select name="language" id="language" class="uk-select" v-model="state.language.id">
                        <option :value="0">- Make a choice -</option>
                        <option v-for="language in languages" :value="language.id"
                                :disabled="selected.includes(language.id)">
                            {{ language.name }}
                        </option>
                    </select>
                </div>
                <div class="uk-margin">
                    <label for="language-optional">
                        <input id="language-optional" class="uk-checkbox" type="checkbox"
                               v-model="state.language.optional">
                        Optional
                    </label>
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
import { useMainStore } from '@admin/stores/main'
import { storeToRefs } from 'pinia/dist/pinia.esm-browser'
import UIKit from 'uikit'
import { useState } from './language-select-modal.state'

export default {
    name: 'language-select-modal',
    props: {
        selected: {
            type: Array,
            default: () => []
        }
    },
    setup(props, ctx) {
        const store = useMainStore()
        const { languages } = storeToRefs(store)
        const ui = {
            close: () => UIKit.modal('#ability-select-modal').hide()
        }
        const state = useState(languages, ctx, ui)
        return { languages, state }
    }
}
</script>